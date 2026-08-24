<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ══════════════════════════════════════════
    //  صفحة المتجر — بحث وفلاتر
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Product::active()->with(['images', 'category']);

        // ─── فلتر البحث ───
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_desc', 'like', "%{$search}%")
                  ->orWhere('era', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }

        // ─── فلتر التصنيف ───
        if ($category = $request->get('category')) {
            $cat = Category::where('slug', $category)->first();
            if ($cat) {
                $childIds = $cat->children()->pluck('id')->push($cat->id);
                $query->whereIn('category_id', $childIds);
            }
        }

        // ─── فلتر السعر ───
        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        // ─── فلتر الحالة الخاصة ───
        if ($request->boolean('rare'))     $query->where('is_rare', true);
        if ($request->boolean('featured')) $query->where('is_featured', true);

        // ─── الترتيب ───
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'oldest'     => $query->oldest(),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->orderBy('order')->get();

        // نطاق السعر للـ Slider
        $priceRange = [
            'min' => Product::active()->min('price') ?? 0,
            'max' => Product::active()->max('price') ?? 99999,
        ];

        return view('shop.index', compact(
            'products', 'categories', 'priceRange'
        ));
    }

    // ══════════════════════════════════════════
    //  صفحة المنتج
    // ══════════════════════════════════════════
    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['images', 'category.parent'])
            ->where('slug', $slug)
            ->firstOrFail();

        // ─── عداد المشاهدات (مرة واحدة لكل جلسة لكل منتج) ───
        $viewedKey = 'viewed_product_' . $product->id;
        if (! session()->has($viewedKey)) {
            $product->increment('views_count');
            session()->put($viewedKey, true);
        }

        // ─── منتجات مشابهة ───
        $related = Product::active()
            ->with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        // ─── هل في المفضلة؟ ───
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = auth()->user()->wishlistItems()
                ->where('product_id', $product->id)
                ->exists();
        }

        // ─── هل في السلة؟ ───
        $inCart = false;
        if (auth()->check()) {
            $inCart = auth()->user()->cartItems()
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('product.show', compact(
            'product', 'related', 'inWishlist', 'inCart'
        ));
    }
}
