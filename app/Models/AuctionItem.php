<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            'created_at'      => 'datetime',
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
