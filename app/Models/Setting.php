<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'key';
    protected $keyType    = 'string';

    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'settings.all';

    /**
     * قراءة إعداد بمفتاح مع قيمة افتراضية
     *
     * Setting::get('site_name', 'قطعة وقصة')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::cached()->get($key, $default);
    }

    /**
     * حفظ أو تحديث إعداد
     *
     * Setting::set('site_name', 'قطعة وقصة الجديد')
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::flushCache();
    }

    /**
     * قيمة تواصل حقيقية فقط (هاتف، واتساب، بريد…)
     * تُرجع null إذا كانت فارغة أو ما زالت قيمة تجريبية مثل «+966 XX XXX XXXX»
     * حتى لا تظهر بيانات وهمية للزوار
     */
    public static function contact(string $key): ?string
    {
        $value = trim((string) static::get($key));
        return ($value === '' || preg_match('/x{2,}/i', $value)) ? null : $value;
    }

    /**
     * رابط واتساب جاهز (wa.me يقبل الأرقام فقط) أو null إذا لم يُضبط الرقم
     */
    public static function whatsappLink(?string $text = null): ?string
    {
        $number = preg_replace('/\D/', '', (string) static::contact('site_whatsapp'));
        if ($number === '') return null;

        return "https://wa.me/{$number}" . ($text ? '?text=' . rawurlencode($text) : '');
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * كل الإعدادات من الكاش (استعلام واحد بدلاً من استعلام لكل إعداد)
     */
    private static function cached(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::pluck('value', 'key'));
    }
}
