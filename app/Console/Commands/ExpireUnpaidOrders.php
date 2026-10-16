<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\MoyasarGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * إلغاء طلبات الدفع الإلكتروني التي لم تُدفع خلال المهلة وإعادة قطعها للمخزون
 * (كثير من القطع فريدة — لا يجب أن تبقى محجوزة لطلب متروك)
 */
class ExpireUnpaidOrders extends Command
{
    protected $signature   = 'orders:expire-unpaid';
    protected $description = 'إلغاء طلبات الدفع الإلكتروني غير المدفوعة بعد انتهاء المهلة';

    public function handle(MoyasarGateway $gateway): int
    {
        $ttl = (int) config('services.moyasar.unpaid_ttl_minutes', 60);

        $orders = Order::where('payment_method', 'online')
            ->where('payment_status', '!=', 'paid')
            ->whereNotIn('status', Order::CLOSED_STATUSES)
            ->where('created_at', '<', now()->subMinutes($ttl))
            ->get();

        $cancelled = 0;

        foreach ($orders as $order) {
            // تحقق أخير: ربما دفع العميل ولم يصلنا الإشعار
            if ($order->payment_reference && MoyasarGateway::enabled()) {
                try {
                    if ($gateway->settle($order)) continue;
                } catch (\Throwable $e) {
                    report($e);
                    continue;   // لا نلغي طلباً لم نتمكن من التحقق من حالته
                }
            }

            DB::transaction(function () use ($order) {
                $order->changeStatus('CANCELLED');
                $order->update(['payment_status' => 'failed']);
            });
            $cancelled++;
        }

        $this->info("تم إلغاء {$cancelled} طلب غير مدفوع.");

        return self::SUCCESS;
    }
}
