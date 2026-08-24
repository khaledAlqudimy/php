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
            'site_phone'         => '+966 XX XXX XXXX',
            'site_address'       => 'المملكة العربية السعودية',
            'site_whatsapp'      => '+966XXXXXXXXX',

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

            // ─── الباحث الشخصي ───
            'personal_shopper_enabled' => '1',
            'auction_enabled'          => '1',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('✅ تم إنشاء إعدادات الموقع الافتراضية');
    }
}
