<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionInterest extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $table = 'auction_interest';

    protected $fillable = [
        'auction_item_id', 'user_id', 'name',
        'phone', 'email', 'max_budget', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'max_budget' => 'decimal:2',
            'created_at' => 'datetime',
        ];
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
