<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $messages = [
            'PROCESSING' => 'بدأنا تجهيز طلبك وتغليف القطع بعناية.',
            'SHIPPED'    => 'تم شحن طلبك وهو في الطريق إليك.',
            'DELIVERED'  => 'تم توصيل طلبك. نتمنى أن تحمل قطعتك الجديدة قصة جميلة في بيتك.',
            'CANCELLED'  => 'تم إلغاء طلبك. إذا كان لديك أي استفسار فنحن هنا لمساعدتك.',
            'REFUNDED'   => 'تم استرجاع مبلغ طلبك.',
        ];

        return (new MailMessage)
            ->subject("طلبك {$this->order->order_number}: {$this->order->statusLabel()}")
            ->greeting('مرحباً ' . $notifiable->name)
            ->line("حالة طلبك رقم **{$this->order->order_number}** أصبحت: **{$this->order->statusLabel()}**")
            ->line($messages[$this->order->status] ?? '')
            ->action('متابعة طلباتي', route('account.orders'))
            ->salutation('فريق قطعة وقصة');
    }
}
