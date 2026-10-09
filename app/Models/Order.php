<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
