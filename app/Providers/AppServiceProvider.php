<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ─── تعريب pagination ───
        Paginator::defaultView('vendor.pagination.arabic');
        Paginator::defaultSimpleView('vendor.pagination.arabic-simple');

        // ─── فرض HTTPS في الإنتاج ───
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
