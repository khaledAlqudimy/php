<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])->latest();

        if ($s = $request->get('q')) {
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%");
            });
        }

        if ($cat = $request->get('category')) {
            $query->where('category_id', $cat);
        }

        if ($request->get('status') === 'active')   $query->where('is_active', true);
        if ($request->get('status') === 'inactive') $query->where('is_active', false);
        if ($request->get('rare'))                   $query->where('is_rare', true);

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::whereNull('parent_id')->orderBy('order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:200'],
            'category_id'       => ['required', 'exists:categories,id'],
            'price'             => ['required', 'numeric', 'min:0'],
            'compare_at_price'  => ['nullable', 'numeric', 'min:0'],
            'stock'             => ['required', 'integer', 'min:0'],
            'short_desc'        => ['nullable', 'string', 'max:300'],
            'description'       => ['nullable', 'string'],
            'story'             => ['nullable', 'string'],
            'era'               => ['nullable', 'string', 'max:80'],
            'country_of_origin' => ['nullable', 'string', 'max:60'],
            'material'          => ['nullable', 'string', 'max:80'],
            'dimensions'        => ['nullable', 'string', 'max:80'],
            'condition'         => ['nullable', 'string', 'max:80'],
            'is_featured'       => ['boolean'],
            'is_rare'           => ['boolean'],
            'is_active'         => ['boolean'],
            'images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $data['slug']        = $this->uniqueSlug(Str::slug($request->title));
        $data['sku']         = $this->generateSku();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_rare']     = $request->boolean('is_rare');
        $data['is_active']   = $request->boolean('is_active', true);

        $product = Product::create($data);

        // ─── رفع الصور ───
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store("products/{$product->id}", 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'url'        => $path,
                    'order'      => $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "تم إضافة المنتج «{$product->title}» بنجاح.");
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:200'],
            'category_id'       => ['required', 'exists:categories,id'],
            'price'             => ['required', 'numeric', 'min:0'],
            'compare_at_price'  => ['nullable', 'numeric', 'min:0'],
            'stock'             => ['required', 'integer', 'min:0'],
            'short_desc'        => ['nullable', 'string', 'max:300'],
            'description'       => ['nullable', 'string'],
            'story'             => ['nullable', 'string'],
            'era'               => ['nullable', 'string', 'max:80'],
            'country_of_origin' => ['nullable', 'string', 'max:60'],
            'material'          => ['nullable', 'string', 'max:80'],
            'dimensions'        => ['nullable', 'string', 'max:80'],
            'condition'         => ['nullable', 'string', 'max:80'],
            'images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_rare']     = $request->boolean('is_rare');
        $data['is_active']   = $request->boolean('is_active');

        $product->update($data);

        if ($request->hasFile('images')) {
            $nextOrder = $product->images()->max('order') + 1;
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store("products/{$product->id}", 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'url'        => $path,
                    'order'      => $nextOrder + $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "تم تحديث المنتج «{$product->title}».");
    }

    public function destroy(Product $product)
    {
        // حذف الصور من الـ Storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->url);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }

    public function deleteImage(int $id)
    {
        $image = ProductImage::findOrFail($id);
        Storage::disk('public')->delete($image->url);
        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * جعل الصورة رئيسية (تظهر أولاً في المتجر وبطاقة المنتج)
     */
    public function makeMainImage(int $id)
    {
        $image  = ProductImage::findOrFail($id);
        $others = ProductImage::where('product_id', $image->product_id)
            ->where('id', '!=', $image->id)
            ->orderBy('order')
            ->pluck('id');

        $image->update(['order' => 0]);
        foreach ($others as $i => $otherId) {
            ProductImage::whereKey($otherId)->update(['order' => $i + 1]);
        }

        return response()->json(['success' => true]);
    }

    // ─── Helpers ───
    private function uniqueSlug(string $base): string
    {
        $slug = $base ?: 'product';
        $i    = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    private function generateSku(): string
    {
        do {
            $sku = 'QQ-' . strtoupper(Str::random(6));
        } while (Product::where('sku', $sku)->exists());
        return $sku;
    }
}
