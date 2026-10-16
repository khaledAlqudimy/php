<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;

class WishlistController extends Controller
{
    // ══════════════════════════════════════════
    //  صفحة المفضلة
    // ══════════════════════════════════════════
    public function index()
    {
        $items = auth()->user()
            ->wishlistItems()
            ->with(['product.images', 'product.category'])
            ->latest('created_at')
            ->get();

        return view('account.wishlist', compact('items'));
    }

    // ══════════════════════════════════════════
    //  إضافة / حذف من المفضلة (JSON Toggle)
    // ══════════════════════════════════════════
    public function toggle(int $productId)
    {
        $existing = WishlistItem::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['added' => false, 'message' => 'تمت الإزالة من المفضلة']);
        }

        $product = Product::active()->findOrFail($productId);

        WishlistItem::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
        ]);

        return response()->json(['added' => true, 'message' => 'تمت الإضافة للمفضلة']);
    }
}
