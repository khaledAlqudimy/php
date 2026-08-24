<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ─── AuctionItem ─────────────────────────────────────────────────────────────
class AuctionItem extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id', 'estimated_price', 'deadline', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'estimated_price' => 'decimal:2',
            'deadline'        => 'datetime',
            'is_active'       => 'boolean',
        ];
    }

    public function isOpen(): bool
    {
        return $this->is_active && $this->deadline->isFuture();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function interests()
    {
        return $this->hasMany(AuctionInterest::class);
    }
}

// ─── AuctionInterest ─────────────────────────────────────────────────────────
class AuctionInterest extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'auction_item_id', 'user_id', 'name',
        'phone', 'email', 'max_budget', 'notes',
    ];

    protected function casts(): array
    {
        return ['max_budget' => 'decimal:2'];
    }

    public function auctionItem()
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
