<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\Request;

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

        WishlistItem::create([
            'user_id'    => auth()->id(),
            'product_id' => $productId,
        ]);

        return response()->json(['added' => true, 'message' => 'تمت الإضافة للمفضلة']);
    }
}
