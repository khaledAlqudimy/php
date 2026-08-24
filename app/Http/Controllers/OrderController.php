<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
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

        $addresses   = auth()->user()->addresses()->orderByDesc('is_default')->get();
        $subtotal    = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $shippingFee = (float) Setting::get('shipping_fee', 30);
        $freeMin     = (float) Setting::get('free_shipping_min', 500);
        $vatPercent  = (float) Setting::get('vat_percent', 15);
        $vatEnabled  = (bool)  Setting::get('vat_enabled', 1);

        if ($subtotal >= $freeMin) $shippingFee = 0;
        $taxAmount = $vatEnabled ? round($subtotal * ($vatPercent / 100), 2) : 0;
        $total     = $subtotal + $shippingFee + $taxAmount;

        return view('checkout.index', compact(
            'cartItems', 'addresses', 'subtotal',
            'shippingFee', 'taxAmount', 'vatPercent',
            'vatEnabled', 'freeMin', 'total'
        ));
    }

    // ══════════════════════════════════════════
    //  تأكيد وحفظ الطلب
    // ══════════════════════════════════════════
    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id'     => ['required', 'exists:addresses,id'],
            'payment_method' => ['required', 'in:cod,bank_transfer,online'],
            'coupon_code'    => ['nullable', 'string'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ], [
            'address_id.required'     => 'اختر عنوان التوصيل',
            'payment_method.required' => 'اختر طريقة الدفع',
        ]);

        // تحقق أن العنوان يخص المستخدم
        $address = auth()->user()->addresses()->findOrFail($request->address_id);

        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة.');
        }

        // ─── حساب الإجماليات ───
        $subtotal    = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $shippingFee = (float) Setting::get('shipping_fee', 30);
        $freeMin     = (float) Setting::get('free_shipping_min', 500);
        $vatPercent  = (float) Setting::get('vat_percent', 15);
        $vatEnabled  = (bool)  Setting::get('vat_enabled', 1);

        if ($subtotal >= $freeMin) $shippingFee = 0;
        $taxAmount = $vatEnabled ? round($subtotal * ($vatPercent / 100), 2) : 0;

        // ─── تطبيق الكوبون ───
        $discount = 0;
        $coupon   = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();

            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal + $shippingFee + $taxAmount - $discount;

        // ─── إنشاء الطلب داخل Transaction ───
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => auth()->id(),
                'address_id'     => $address->id,
                'status'         => 'PENDING',
                'subtotal'       => $subtotal,
                'shipping_fee'   => $shippingFee,
                'tax_amount'     => $taxAmount,
                'discount'       => $discount,
                'total'          => $total,
                'coupon_id'      => $coupon?->id,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
            ]);

            // إنشاء عناصر الطلب
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'title'      => $item->product->title,
                    'price'      => $item->product->price,
                    'quantity'   => $item->quantity,
                ]);

                // تقليل المخزون
                $item->product->decrement('stock', $item->quantity);
            }

            // زيادة عداد استخدام الكوبون
            if ($coupon) {
                $coupon->increment('used_count');
            }

            // تفريغ السلة
            auth()->user()->cartItems()->delete();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
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
        $subtotal = auth()->user()
            ->cartItems()
            ->with('product')
            ->get()
            ->sum(fn($i) => $i->product->price * $i->quantity);

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

        $discount  = $coupon->calculateDiscount($subtotal);
        $shippingFee = (float) Setting::get('shipping_fee', 30);
        $freeMin     = (float) Setting::get('free_shipping_min', 500);
        $vatPercent  = (float) Setting::get('vat_percent', 15);
        $vatEnabled  = (bool)  Setting::get('vat_enabled', 1);

        if ($subtotal >= $freeMin) $shippingFee = 0;
        $taxAmount = $vatEnabled ? round($subtotal * ($vatPercent / 100), 2) : 0;
        $newTotal  = max(0, $subtotal + $shippingFee + $taxAmount - $discount);

        $label = $coupon->type === 'PERCENTAGE'
            ? "خصم {$coupon->value}%"
            : 'خصم ' . number_format($coupon->value, 0) . ' ر.س';

        return response()->json([
            'valid'     => true,
            'message'   => $label,
            'discount'  => number_format($discount, 0),
            'new_total' => number_format($newTotal, 0),
        ]);
    }
}
