<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'sku', 'title', 'slug', 'short_desc', 'description', 'story',
        'price', 'compare_at_price', 'country_of_origin', 'era',
        'material', 'dimensions', 'condition', 'stock',
        'is_featured', 'is_rare', 'is_active', 'views_count',
        'video_url', 'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price'            => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_featured'      => 'boolean',
            'is_rare'          => 'boolean',
            'is_active'        => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

    // ─── Helper Methods ───
    public function hasDiscount(): bool
    {
        return $this->compare_at_price && $this->compare_at_price > $this->price;
    }

    public function discountPercent(): int
    {
        if (! $this->hasDiscount()) return 0;
        return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
    }

    public function mainImage(): ?string
    {
        // استخدام الصور المحمّلة مسبقاً (with('images')) بدل استعلام جديد لكل بطاقة
        if ($this->relationLoaded('images')) {
            return $this->images->sortBy('order')->first()?->url;
        }

        return $this->images()->value('url');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // ─── Scopes ───
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeRare($query)
    {
        return $query->where('is_rare', true)->where('is_active', true);
    }

    public function scopeLatest($query)
    {
        return $query->where('is_active', true)->orderByDesc('created_at');
    }

    // ─── العلاقات ───
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function auctionItem()
    {
        return $this->hasOne(AuctionItem::class);
    }
}
