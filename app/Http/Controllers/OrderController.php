<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderTotals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ══════════════════════════════════════════
    //  صفحة إتمام الطلب
    // ══════════════════════════════════════════
    public function checkout()
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with(['product.images'])
            ->get();

        // إذا كانت السلة فارغة → ارجع للسلة
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'سلتك فارغة. أضف قطعاً قبل إتمام الطلب.');
        }

        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'addresses' => $addresses,
        ] + OrderTotals::calculate($cartItems));
    }

    // ══════════════════════════════════════════
    //  تأكيد وحفظ الطلب
    // ══════════════════════════════════════════
    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id'     => ['required', 'exists:addresses,id'],
            'payment_method' => ['required', 'in:cod,bank_transfer'],
            'coupon_code'    => ['nullable', 'string'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ], [
            'address_id.required'     => 'اختر عنوان التوصيل',
            'payment_method.required' => 'اختر طريقة الدفع',
        ]);

        // تحقق أن العنوان يخص المستخدم
        $address = auth()->user()->addresses()->findOrFail($request->address_id);

        // ─── إنشاء الطلب داخل Transaction مع قفل صفوف المنتجات ───
        // القفل يمنع بيع نفس القطعة لعميلين في نفس اللحظة
        try {
            $order = DB::transaction(function () use ($request, $address) {
                $cartItems = auth()->user()->cartItems()->get();

                if ($cartItems->isEmpty()) {
                    throw new CheckoutException('السلة فارغة.');
                }

                $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($cartItems as $item) {
                    $product = $products->get($item->product_id);

                    if (! $product || ! $product->is_active) {
                        throw new CheckoutException('إحدى القطع في سلتك لم تعد متاحة. راجع السلة.');
                    }
                    if ($product->stock < $item->quantity) {
                        throw new CheckoutException("القطعة «{$product->title}» لم تعد متوفرة بالكمية المطلوبة.");
                    }

                    $item->setRelation('product', $product);
                }

                // ─── الكوبون (قفل لمنع تجاوز حد الاستخدام) ───
                $coupon = null;
                if ($request->filled('coupon_code')) {
                    $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))
                        ->lockForUpdate()
                        ->first();
                }

                $totals = OrderTotals::calculate($cartItems, $coupon);
                $coupon = $totals['discount'] > 0 ? $coupon : null;

                $order = Order::create([
                    'user_id'        => auth()->id(),
                    'address_id'     => $address->id,
                    'status'         => 'PENDING',
                    'subtotal'       => $totals['subtotal'],
                    'shipping_fee'   => $totals['shippingFee'],
                    'tax_amount'     => $totals['taxAmount'],
                    'discount'       => $totals['discount'],
                    'total'          => $totals['total'],
                    'coupon_id'      => $coupon?->id,
                    'payment_method' => $request->payment_method,
                    'notes'          => $request->notes,
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'title'      => $item->product->title,
                        'price'      => $item->product->price,
                        'quantity'   => $item->quantity,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                }

                $coupon?->increment('used_count');

                auth()->user()->cartItems()->delete();

                return $order;
            });
        } catch (CheckoutException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'حدث خطأ أثناء تأكيد الطلب. حاول مرة أخرى.');
        }

        return redirect()->route('account.orders')
            ->with('success', "تم تأكيد طلبك بنجاح! رقم الطلب: {$order->order_number}");
    }

    // ══════════════════════════════════════════
    //  التحقق من كوبون (JSON)
    // ══════════════════════════════════════════
    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon) {
            return response()->json(['valid' => false, 'message' => 'كود الخصم غير صحيح']);
        }

        // نحسب الـ subtotal من السلة الحالية
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $subtotal  = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);

        if (! $coupon->isValid($subtotal)) {
            $reason = match (true) {
                $coupon->expires_at && $coupon->expires_at->isPast() => 'انتهت صلاحية هذا الكوبون',
                $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit => 'تم استنفاد هذا الكوبون',
                ! $coupon->is_active => 'هذا الكوبون غير نشط',
                $coupon->min_order && $subtotal < $coupon->min_order => 'الحد الأدنى للطلب هو ' . number_format($coupon->min_order, 0) . ' ر.س',
                default => 'الكوبون غير صالح',
            };
            return response()->json(['valid' => false, 'message' => $reason]);
        }

        $totals = OrderTotals::calculate($cartItems, $coupon);

        $label = $coupon->type === 'PERCENTAGE'
            ? "خصم {$coupon->value}%"
            : 'خصم ' . number_format($coupon->value, 0) . ' ر.س';

        return response()->json([
            'valid'     => true,
            'message'   => $label,
            'discount'  => number_format($totals['discount'], 0),
            'new_total' => number_format($totals['total'], 0),
        ]);
    }
}
