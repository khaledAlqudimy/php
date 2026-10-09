<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * التكامل مع Moyasar عبر الفواتير (Invoices):
 * ننشئ فاتورة بمبلغ الطلب → نحوّل العميل لصفحة الدفع الآمنة لدى Moyasar
 * → عند العودة نتحقق من حالة الفاتورة من Moyasar مباشرة (لا نثق بأي قيمة قادمة من المتصفح).
 */
class MoyasarGateway
{
    public static function enabled(): bool
    {
        return filled(config('services.moyasar.secret_key'));
    }

    /**
     * إنشاء فاتورة للطلب
     *
     * @return array{id: string, url: string}
     */
    public function createInvoice(Order $order): array
    {
        $invoice = $this->request('post', '/invoices', [
            'amount'       => self::toHalalas($order->total),
            'currency'     => 'SAR',
            'description'  => "طلب {$order->order_number} — قطعة وقصة",
            'success_url'  => route('payments.return', $order),
            'back_url'     => route('account.orders'),
            'callback_url' => route('payments.callback'),
            'metadata'     => ['order_number' => $order->order_number],
        ]);

        if (empty($invoice['id']) || empty($invoice['url'])) {
            throw new RuntimeException('Moyasar: رد غير متوقع عند إنشاء الفاتورة');
        }

        return ['id' => $invoice['id'], 'url' => $invoice['url']];
    }

    public function fetchInvoice(string $id): array
    {
        return $this->request('get', '/invoices/' . rawurlencode($id));
    }

    /**
     * هل الفاتورة مدفوعة فعلاً وبنفس مبلغ الطلب وعملته؟
     */
    public function isPaidFor(array $invoice, Order $order): bool
    {
        return ($invoice['status'] ?? null) === 'paid'
            && (int) ($invoice['amount'] ?? 0) === self::toHalalas($order->total)
            && strtoupper($invoice['currency'] ?? '') === 'SAR';
    }

    /**
     * التحقق من Moyasar وتحديث الطلب إذا دُفع.
     * آمن للاستدعاء أكثر من مرة (العودة من صفحة الدفع + إشعار Moyasar في نفس الوقت).
     *
     * @return bool هل الطلب مدفوع الآن؟
     */
    public function settle(Order $order): bool
    {
        if ($order->payment_status === 'paid') {
            return true;
        }
        if (! $order->payment_reference) {
            return false;
        }

        $invoice = $this->fetchInvoice($order->payment_reference);
        if (! $this->isPaidFor($invoice, $order)) {
            return false;
        }

        $justPaid = DB::transaction(function () use ($order) {
            $locked = Order::lockForUpdate()->find($order->id);
            if ($locked->payment_status === 'paid') {
                return false;
            }

            // دُفع بعد انتهاء مهلة الحجز وإلغاء الطلب تلقائياً → نعيد فتحه ونحجز القطع من جديد
            if ($locked->isClosed()) {
                $locked->changeStatus('PENDING');
            }

            $locked->markPaid($locked->payment_reference);
            return true;
        });

        $order->refresh();

        if ($justPaid) {
            OrderNotifier::placed($order);
        }

        return true;
    }

    public static function toHalalas(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function request(string $method, string $path, array $data = []): array
    {
        $response = Http::withBasicAuth((string) config('services.moyasar.secret_key'), '')
            ->acceptJson()
            ->timeout(20)
            ->{$method}(rtrim(config('services.moyasar.base_url'), '/') . $path, $data);

        if ($response->failed()) {
            throw new RuntimeException("Moyasar: HTTP {$response->status()} — " . $response->body());
        }

        return $response->json() ?? [];
    }
}
