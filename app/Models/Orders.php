<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// ─── Order ───────────────────────────────────────────────────────────────────
class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'status',
        'subtotal', 'shipping_fee', 'tax_amount', 'discount', 'total',
        'coupon_id', 'payment_method', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'     => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'tax_amount'   => 'decimal:2',
            'discount'     => 'decimal:2',
            'total'        => 'decimal:2',
        ];
    }

    // توليد رقم الطلب تلقائياً
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'PENDING'    => 'قيد الانتظار',
            'PROCESSING' => 'قيد المعالجة',
            'SHIPPED'    => 'تم الشحن',
            'DELIVERED'  => 'تم التوصيل',
            'CANCELLED'  => 'ملغي',
            'REFUNDED'   => 'مسترجع',
            default      => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'PENDING'    => 'yellow',
            'PROCESSING' => 'blue',
            'SHIPPED'    => 'indigo',
            'DELIVERED'  => 'green',
            'CANCELLED'  => 'red',
            'REFUNDED'   => 'gray',
            default      => 'gray',
        };
    }

    public function user()    { return $this->belongsTo(User::class); }
    public function address() { return $this->belongsTo(Address::class); }
    public function coupon()  { return $this->belongsTo(Coupon::class); }
    public function items()   { return $this->hasMany(OrderItem::class); }
}

// ─── OrderItem ───────────────────────────────────────────────────────────────
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

// ─── Coupon ──────────────────────────────────────────────────────────────────
class Coupon extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'code', 'type', 'value', 'min_order',
        'usage_limit', 'used_count', 'is_active', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value'      => 'decimal:2',
            'min_order'  => 'decimal:2',
            'is_active'  => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function isValid(float $orderTotal): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->min_order && $orderTotal < $this->min_order) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'PERCENTAGE') {
            return round($subtotal * ($this->value / 100), 2);
        }
        return min((float) $this->value, $subtotal);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
