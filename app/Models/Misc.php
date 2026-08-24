<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ─── PersonalShopperRequest ──────────────────────────────────────────────────
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

// ─── Notification ────────────────────────────────────────────────────────────
class Notification extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'title', 'message', 'is_read'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ───
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}

// ─── Setting ─────────────────────────────────────────────────────────────────
class Setting extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'key';
    protected $keyType    = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * قراءة إعداد بمفتاح مع قيمة افتراضية
     *
     * Setting::get('site_name', 'قطعة وقصة')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::find($key);
        return $setting ? $setting->value : $default;
    }

    /**
     * حفظ أو تحديث إعداد
     *
     * Setting::set('site_name', 'قطعة وقصة الجديد')
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
