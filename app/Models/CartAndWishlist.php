<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ─── ProductImage ────────────────────────────────────────────────────────────
class ProductImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'url', 'alt_text', 'order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

// ─── CartItem ────────────────────────────────────────────────────────────────
class CartItem extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $table = 'cart';

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subtotal(): float
    {
        return $this->product->price * $this->quantity;
    }
}

// ─── WishlistItem ────────────────────────────────────────────────────────────
class WishlistItem extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $table = 'wishlist';

    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

// ─── Address ─────────────────────────────────────────────────────────────────
class Address extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'full_name', 'phone', 'country',
        'city', 'district', 'street', 'postal', 'is_default',
    ];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
