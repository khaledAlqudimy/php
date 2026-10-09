<?php

return [

    'name'  => env('APP_NAME', 'قطعة وقصة'),
    'env'   => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url'   => env('APP_URL', 'http://localhost'),

    'locale'          => 'ar',
    'fallback_locale' => 'ar',
    'faker_locale'    => 'ar_SA',

    'timezone' => 'Asia/Riyadh',

    'key'    => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',


];
