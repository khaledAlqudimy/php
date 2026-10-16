<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL',    'admin@qet3a-w-qesa.sa');
        $password = env('ADMIN_PASSWORD');

        // لا توجد كلمة مرور افتراضية — كلمة مرور معروفة تعني لوحة إدارة مكشوفة
        if (blank($password) || strlen($password) < 10) {
            $this->command->error('❌ ضع ADMIN_PASSWORD في ملف .env (10 أحرف على الأقل) ثم أعد التشغيل.');
            return;
        }

        // إذا كان المدير موجوداً لا نكرره
        if (User::where('email', $email)->exists()) {
            $this->command->info("✅ المدير موجود مسبقاً: {$email}");
            return;
        }

        User::create([
            'name'      => 'مدير قطعة وقصة',
            'email'     => $email,
            'password'  => Hash::make($password),
            'role'      => 'SUPER_ADMIN',
            'is_active' => true,
        ]);

        $this->command->info("✅ تم إنشاء حساب المدير: {$email}");
        $this->command->warn("⚠️  غيّر كلمة المرور فوراً بعد أول دخول!");
    }
}
