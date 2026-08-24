<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDatabase extends Command
{
    protected $signature   = 'db:verify';
    protected $description = 'التحقق من صحة هيكل قاعدة البيانات — اختبار المرحلة 2';

    // الجداول المطلوبة الـ 15
    private array $requiredTables = [
        'users', 'addresses', 'categories', 'products',
        'product_images', 'cart', 'wishlist', 'coupons',
        'orders', 'order_items', 'auction_items',
        'auction_interest', 'personal_shopper_requests',
        'notifications', 'settings',
    ];

    public function handle(): int
    {
        $this->info('');
        $this->info('═══ اختبار المرحلة 2: قاعدة البيانات ═══');
        $this->info('');

        $allPassed = true;

        // ── اختبار 1: وجود الجداول ──
        $this->info('📋 اختبار 1: الجداول الـ 15');
        foreach ($this->requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ {$table} ({$count} سجل)");
            } else {
                $this->error("   ❌ جدول مفقود: {$table}");
                $allPassed = false;
            }
        }

        $this->info('');

        // ── اختبار 2: حساب المدير ──
        $this->info('📋 اختبار 2: حساب المدير');
        $admin = DB::table('users')->where('role', 'SUPER_ADMIN')->first();
        if ($admin) {
            $this->line("   ✅ مدير موجود: {$admin->email}");
        } else {
            $this->error('   ❌ لا يوجد حساب SUPER_ADMIN — شغّل: php artisan db:seed');
            $allPassed = false;
        }

        $this->info('');

        // ── اختبار 3: التصنيفات ──
        $this->info('📋 اختبار 3: التصنيفات الأولية');
        $catCount = DB::table('categories')->count();
        if ($catCount >= 6) {
            $this->line("   ✅ {$catCount} تصنيفات موجودة");
        } else {
            $this->error("   ❌ التصنيفات ناقصة ({$catCount}/6) — شغّل: php artisan db:seed");
            $allPassed = false;
        }

        $this->info('');

        // ── اختبار 4: الإعدادات ──
        $this->info('📋 اختبار 4: إعدادات الموقع');
        $settingCount = DB::table('settings')->count();
        if ($settingCount >= 10) {
            $this->line("   ✅ {$settingCount} إعداد موجود");
        } else {
            $this->error("   ❌ الإعدادات ناقصة ({$settingCount}/15+)");
            $allPassed = false;
        }

        $this->info('');
        $this->info('═══════════════════════════════');

        if ($allPassed) {
            $this->info('🎉 المرحلة 2 اجتازت جميع الاختبارات!');
            $this->info('   الخطوة التالية → المرحلة 3: نظام المصادقة');
        } else {
            $this->error('❌ بعض الاختبارات فشلت — راجع الأخطاء أعلاه');
        }

        $this->info('');

        return $allPassed ? self::SUCCESS : self::FAILURE;
    }
}
