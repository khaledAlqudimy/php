<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalShopperRequest extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $table = 'personal_shopper_requests';

    protected $fillable = [
        'user_id', 'item_type', 'era', 'country_of_origin',
        'budget', 'description', 'reference_images', 'status',
    ];

    protected function casts(): array
    {
        return [
            'budget'           => 'decimal:2',
            'reference_images' => 'array',   // JSON → PHP array تلقائياً
            'created_at'      => 'datetime',
        ];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'NEW'         => 'جديد',
            'IN_PROGRESS' => 'قيد المعالجة',
            'FULFILLED'   => 'تم التنفيذ',
            'CLOSED'      => 'مغلق',
            default       => $this->status,
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
