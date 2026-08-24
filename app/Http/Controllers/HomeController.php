<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // ─── الأقسام التسعة للصفحة الرئيسية ───

        // 1. التصنيفات الجذرية (بدون parent)
        $categories = Category::whereNull('parent_id')
            ->orderBy('order')
            ->take(6)
            ->get();

        // 2. آخر المنتجات
        $latestProducts = Product::active()
            ->with(['images', 'category'])
            ->latest()
            ->take(8)
            ->get();

        // 3. المنتجات المميزة
        $featuredProducts = Product::featured()
            ->with(['images', 'category'])
            ->latest()
            ->take(6)
            ->get();

        // 4. القطع النادرة
        $rareProducts = Product::rare()
            ->with(['images', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('home.index', compact(
            'categories',
            'latestProducts',
            'featuredProducts',
            'rareProducts',
        ));
    }
}
