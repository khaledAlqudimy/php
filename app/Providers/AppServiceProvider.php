<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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

        // ─── رسالة إعادة تعيين كلمة المرور بالعربي ───
        ResetPassword::toMailUsing(function ($user, string $token) {
            $url = route('password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('إعادة تعيين كلمة المرور — قطعة وقصة')
                ->greeting('مرحباً ' . $user->name)
                ->line('وصلنا طلب لإعادة تعيين كلمة المرور لحسابك في قطعة وقصة.')
                ->action('إعادة تعيين كلمة المرور', $url)
                ->line('الرابط صالح لمدة 60 دقيقة.')
                ->line('إذا لم تطلب ذلك فتجاهل هذه الرسالة، وكلمة مرورك الحالية ستبقى كما هي.')
                ->salutation('فريق قطعة وقصة');
        });

        // ─── فرض HTTPS في الإنتاج ───
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
