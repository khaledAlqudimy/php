<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * تأكيد الطلب للعميل (للدفع الإلكتروني: يُرسل بعد نجاح الدفع)
 */
class OrderPlaced extends Notification
{
    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order->loadMissing('items');

        $mail = (new MailMessage)
            ->subject("تأكيد طلبك {$order->order_number} — قطعة وقصة")
            ->greeting('شكراً لك ' . $notifiable->name)
            ->line("استلمنا طلبك رقم **{$order->order_number}** وسنبدأ تجهيزه بعناية.");

        foreach ($order->items as $item) {
            $mail->line("• {$item->title} × {$item->quantity} — " . number_format($item->price * $item->quantity, 2) . ' ر.س');
        }

        $mail->line('**الإجمالي: ' . number_format($order->total, 2) . ' ر.س** (شامل الضريبة والشحن)')
            ->line('طريقة الدفع: ' . $order->paymentLabel());

        if ($order->payment_method === 'bank_transfer') {
            $mail->line('---')->line('**بيانات التحويل البنكي:**');
            foreach ([['bank_name', 'البنك'], ['bank_account_name', 'اسم المستفيد'], ['bank_iban', 'الآيبان']] as [$key, $label]) {
                if ($value = Setting::contact($key)) {
                    $mail->line("{$label}: {$value}");
                }
            }
            $mail->line("يرجى ذكر رقم الطلب {$order->order_number} في وصف التحويل. يُشحن الطلب بعد تأكيد وصول المبلغ.");
        }

        return $mail
            ->action('متابعة طلباتي', route('account.orders'))
            ->salutation('فريق قطعة وقصة');
    }
}
