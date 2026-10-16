<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\OrderTotals;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ══════════════════════════════════════════
    //  عرض صفحة السلة
    // ══════════════════════════════════════════
    public function index()
    {
        $items = auth()->user()
            ->cartItems()
            ->with(['product.images', 'product.category'])
            ->get();

        return view('cart.index', ['items' => $items] + OrderTotals::calculate($items));
    }

    // ══════════════════════════════════════════
    //  إضافة منتج للسلة (JSON)
    // ══════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['integer', 'min:1', 'max:10'],
        ]);

        $product = Product::active()->findOrFail($request->product_id);

        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'المنتج غير متاح'], 422);
        }

        // إذا كان في السلة → زيادة الكمية، وإلا إنشاء جديد
        $item = CartItem::firstOrNew([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
        ]);

        $item->quantity = min(($item->quantity ?? 0) + ($request->quantity ?? 1), $product->stock);
        $item->save();

        $cartCount = auth()->user()->cartItems()->count();

        return response()->json([
            'success'    => true,
            'message'    => 'تمت الإضافة للسلة',
            'cart_count' => $cartCount,
        ]);
    }

    // ══════════════════════════════════════════
    //  تحديث الكمية (JSON)
    // ══════════════════════════════════════════
    public function update(Request $request, int $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $item = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // لا تتجاوز المخزون
        $item->quantity = min($request->quantity, $item->product->stock);
        $item->save();

        // إعادة حساب الإجماليات
        $totals = $this->calculateTotals();

        return response()->json([
            'success'      => true,
            'item_subtotal'=> number_format($item->product->price * $item->quantity, 2),
            'subtotal'     => $totals['subtotal_formatted'],
            'shipping'     => $totals['shipping_formatted'],
            'tax'          => $totals['tax_formatted'],
            'total'        => $totals['total_formatted'],
        ]);
    }

    // ══════════════════════════════════════════
    //  حذف منتج من السلة (JSON)
    // ══════════════════════════════════════════
    public function destroy(int $id)
    {
        CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        $totals    = $this->calculateTotals();
        $cartCount = auth()->user()->cartItems()->count();

        return response()->json([
            'success'    => true,
            'cart_count' => $cartCount,
            'empty'      => $cartCount === 0,
            ...$totals,
        ]);
    }

    // ─── Helper: حساب الإجماليات ───
    private function calculateTotals(): array
    {
        $t = OrderTotals::calculate(auth()->user()->cartItems()->with('product')->get());

        return [
            'subtotal_formatted'  => number_format($t['subtotal'], 2),
            'shipping_formatted'  => number_format($t['shippingFee'], 2),
            'tax_formatted'       => number_format($t['taxAmount'], 2),
            'total_formatted'     => number_format($t['total'], 2),
        ];
    }
}
