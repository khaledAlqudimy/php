<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MoyasarGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private MoyasarGateway $gateway) {}

    // ══════════════════════════════════════════
    //  التحويل لصفحة الدفع الآمنة لدى Moyasar
    // ══════════════════════════════════════════
    public function pay(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);

        if ($order->payment_status === 'paid') {
            return redirect()->route('account.orders')->with('success', 'هذا الطلب مدفوع مسبقاً.');
        }
        if (! $order->isAwaitingOnlinePayment()) {
            return redirect()->route('account.orders')->with('error', 'لا يمكن دفع هذا الطلب. تواصل معنا إذا احتجت مساعدة.');
        }
        if (! MoyasarGateway::enabled()) {
            return redirect()->route('account.orders')->with('error', 'الدفع الإلكتروني غير متاح حالياً.');
        }

        try {
            // إن كان قد دفع فعلاً (مثلاً أغلق الصفحة قبل العودة) فلا ننشئ فاتورة ثانية
            if ($this->gateway->settle($order)) {
                return redirect()->route('account.orders')->with('success', "تم استلام الدفع لطلبك {$order->order_number}.");
            }

            $invoice = $this->gateway->createInvoice($order);
            $order->update(['payment_reference' => $invoice['id']]);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('account.orders')
                ->with('error', 'تعذّر الاتصال ببوابة الدفع. حاول مرة أخرى بعد قليل من صفحة طلباتي.');
        }

        return redirect()->away($invoice['url']);
    }

    // ══════════════════════════════════════════
    //  العودة من Moyasar بعد الدفع
    // ══════════════════════════════════════════
    public function return(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);

        try {
            $paid = $this->gateway->settle($order);
        } catch (\Throwable $e) {
            report($e);
            $paid = false;
        }

        return $paid
            ? redirect()->route('account.orders')->with('success', "تم الدفع بنجاح! رقم طلبك: {$order->order_number}")
            : redirect()->route('account.orders')->with('error', 'لم يكتمل الدفع. يمكنك إعادة المحاولة من زر «ادفع الآن».');
    }

    // ══════════════════════════════════════════
    //  إشعار Moyasar (callback) — لا نثق بمحتواه، نتحقق من Moyasar مباشرة
    // ══════════════════════════════════════════
    public function callback(Request $request)
    {
        // قد يصل معرّف الفاتورة أو الدفعة بحسب نوع الإشعار — نجرّب كل المعرفات
        $ids = collect([
            $request->input('id'), $request->input('invoice_id'),
            $request->input('data.id'), $request->input('data.invoice_id'),
        ])->filter(fn ($v) => is_string($v) && $v !== '')->unique();

        $order = $ids->isEmpty() ? null : Order::whereIn('payment_reference', $ids)->first();

        if ($order) {
            try {
                $this->gateway->settle($order);
            } catch (\Throwable $e) {
                report($e);
                return response('retry', 500);   // Moyasar يعيد المحاولة لاحقاً
            }
        }

        return response('ok');
    }
}
