<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'order'     => ['integer', 'min:0'],
        ]);

        Category::create([
            'name'      => $request->name,
            'slug'      => $this->uniqueSlug($request->name),
            'parent_id' => $request->parent_id ?: null,
            'order'     => $request->order ?? 0,
        ]);

        return back()->with('success', 'تم إضافة التصنيف.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'order' => ['integer', 'min:0'],
        ]);

        $category->update([
            'name'  => $request->name,
            'order' => $request->order ?? $category->order,
        ]);

        return back()->with('success', 'تم تحديث التصنيف.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف تصنيف به منتجات. انقل المنتجات أولاً.');
        }
        $category->delete();
        return back()->with('success', 'تم حذف التصنيف.');
    }

    // ─── slug فريد (اسمان متشابهان كانا يسببان خطأ تكرار في قاعدة البيانات) ───
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i    = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
