<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * تنبيه بريد الموقع بوصول طلب جديد
 */
class NewOrderForAdmin extends Notification
{
    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order->loadMissing(['items', 'user', 'address']);

        $mail = (new MailMessage)
            ->subject("طلب جديد {$order->order_number} — " . number_format($order->total, 0) . ' ر.س')
            ->greeting('طلب جديد في قطعة وقصة')
            ->line('العميل: ' . ($order->user?->name ?? '—') . ' — ' . ($order->user?->phone ?: $order->user?->email))
            ->line('المدينة: ' . ($order->address?->city ?? '—'))
            ->line('طريقة الدفع: ' . $order->paymentLabel() . ' (' . $order->paymentStatusLabel() . ')');

        foreach ($order->items as $item) {
            $mail->line("• {$item->title} × {$item->quantity}");
        }

        return $mail
            ->line('**الإجمالي: ' . number_format($order->total, 2) . ' ر.س**')
            ->action('فتح الطلب في لوحة الإدارة', route('admin.orders.show', $order));
    }
}
