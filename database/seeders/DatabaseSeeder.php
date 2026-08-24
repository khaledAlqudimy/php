<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * ترتيب التشغيل مهم — Admin أولاً ثم التصنيفات ثم الإعدادات
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('     قطعة وقصة — إعداد قاعدة البيانات');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('');

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ تم الانتهاء من إعداد قاعدة البيانات بنجاح!');
        $this->command->info('');
    }
}
