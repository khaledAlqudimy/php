<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'status',
        'subtotal', 'shipping_fee', 'tax_amount', 'discount', 'total',
        'coupon_id', 'payment_method', 'payment_status', 'payment_reference', 'paid_at', 'notes',
    ];

    /** حالات تعني أن الطلب مغلق والقطع عادت للمخزون */
    public const CLOSED_STATUSES = ['CANCELLED', 'REFUNDED'];

    public const PAYMENT_METHODS = [
        'cod'           => 'الدفع عند الاستلام',
        'bank_transfer' => 'تحويل بنكي',
        'online'        => 'دفع إلكتروني (مدى / بطاقة / Apple Pay)',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'     => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'tax_amount'   => 'decimal:2',
            'discount'     => 'decimal:2',
            'total'        => 'decimal:2',
            'paid_at'      => 'datetime',
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

    public function paymentLabel(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? (string) $this->payment_method;
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'paid'   => 'مدفوع',
            'failed' => 'فشل الدفع',
            default  => 'غير مدفوع',
        };
    }

    public function isClosed(): bool
    {
        return in_array($this->status, self::CLOSED_STATUSES);
    }

    /** طلب دفع إلكتروني ينتظر إتمام الدفع (القطع محجوزة له مؤقتاً) */
    public function isAwaitingOnlinePayment(): bool
    {
        return $this->payment_method === 'online'
            && $this->payment_status !== 'paid'
            && ! $this->isClosed();
    }

    /**
     * تغيير الحالة مع ضبط المخزون:
     * الإغلاق (إلغاء/استرجاع) يعيد القطع للمخزون، وإعادة الفتح تحجزها من جديد.
     * يُستدعى داخل transaction.
     */
    public function changeStatus(string $status): void
    {
        $wasClosed = $this->isClosed();
        $willClose = in_array($status, self::CLOSED_STATUSES);

        if ($wasClosed !== $willClose) {
            foreach ($this->items()->with('product')->get() as $item) {
                if (! $item->product) continue;
                $willClose
                    ? $item->product->increment('stock', $item->quantity)
                    : $item->product->decrement('stock', $item->quantity);
            }
        }

        $this->update(['status' => $status]);
    }

    public function markPaid(string $reference): void
    {
        $this->update([
            'payment_status'    => 'paid',
            'payment_reference' => $reference,
            'paid_at'           => now(),
            'status'            => $this->status === 'PENDING' ? 'PROCESSING' : $this->status,
        ]);
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
