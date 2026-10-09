<?php

use Illuminate\Support\Facades\Schedule;

/*
| المهام المجدولة — تتطلب Cron Job واحد على Hostinger يشغّل:
|   php artisan schedule:run
| كل دقيقة (انظر HOSTINGER-SETUP.md)
*/

// إلغاء طلبات الدفع الإلكتروني المتروكة وإعادة قطعها للمخزون
Schedule::command('orders:expire-unpaid')->everyFiveMinutes()->withoutOverlapping();
