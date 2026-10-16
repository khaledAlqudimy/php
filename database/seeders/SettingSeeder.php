<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // ─── معلومات الموقع ───
            'site_name'          => 'قطعة وقصة',
            'site_tagline'       => 'كل قطعة تحمل روحاً وتروي قصة',
            'site_email'         => 'info@qet3a-w-qesa.sa',
            'site_phone'         => '',   // يُضبط من لوحة الإدارة ← الإعدادات
            'site_address'       => 'المملكة العربية السعودية',
            'site_whatsapp'      => '',   // بصيغة دولية: 9665XXXXXXXX

            // ─── السوشيال ───
            'social_instagram'   => '',
            'social_twitter'     => '',
            'social_facebook'    => '',
            'social_snapchat'    => '',

            // ─── الشحن ───
            'shipping_fee'       => '30',
            'free_shipping_min'  => '500',

            // ─── التوصيل ───
            'delivery_days_min'  => '3',
            'delivery_days_max'  => '7',

            // ─── الضريبة ───
            'vat_percent'        => '15',
            'vat_enabled'        => '1',

            // ─── التحويل البنكي (تظهر في إيميل تأكيد الطلب) ───
            'bank_name'          => '',
            'bank_account_name'  => '',
            'bank_iban'          => '',

            // ─── الباحث الشخصي ───
            'personal_shopper_enabled' => '1',
            'auction_enabled'          => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();

        $this->command->info('✅ تم إنشاء إعدادات الموقع الافتراضية');
    }
}
