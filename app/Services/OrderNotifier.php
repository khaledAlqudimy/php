<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Notifications\NewOrderForAdmin;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusChanged;
use Illuminate\Support\Facades\Notification;

/**
 * إرسال إيميلات الطلبات — فشل البريد لا يجب أن يُفشل الطلب نفسه
 */
class OrderNotifier
{
    public static function placed(Order $order): void
    {
        self::safely(function () use ($order) {
            $order->user?->notify(new OrderPlaced($order));

            if ($adminEmail = Setting::contact('site_email')) {
                Notification::route('mail', $adminEmail)->notify(new NewOrderForAdmin($order));
            }
        });
    }

    public static function statusChanged(Order $order): void
    {
        self::safely(fn () => $order->user?->notify(new OrderStatusChanged($order)));
    }

    private static function safely(callable $send): void
    {
        try {
            $send();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
