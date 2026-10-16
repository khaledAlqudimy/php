<?php

return [

    /*
    | بوابة الدفع Moyasar (مدى، Apple Pay، STC Pay، فيزا، ماستركارد)
    | الدفع الإلكتروني يظهر في صفحة الدفع فقط عند ضبط المفتاح السري.
    | مفاتيح التجربة تبدأ بـ sk_test_ ومفاتيح الإنتاج بـ sk_live_
    */
    'moyasar' => [
        'secret_key' => env('MOYASAR_SECRET_KEY'),
        'base_url'   => env('MOYASAR_BASE_URL', 'https://api.moyasar.com/v1'),
        // مدة حجز القطع لطلب لم يُدفع قبل إلغائه تلقائياً
        'unpaid_ttl_minutes' => (int) env('MOYASAR_UNPAID_TTL', 60),
    ],

];
