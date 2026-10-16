<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'title', 'price', 'quantity'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function subtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
