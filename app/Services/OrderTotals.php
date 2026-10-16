<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * حساب إجماليات السلة/الطلب في مكان واحد
 * (السلة، صفحة الدفع، التحقق من الكوبون، وتأكيد الطلب تستخدم نفس الحساب)
 */
class OrderTotals
{
    /**
     * @param  Collection  $items  عناصر السلة مع علاقة product محمّلة
     */
    public static function calculate(Collection $items, ?Coupon $coupon = null): array
    {
        $subtotal    = (float) $items->sum(fn ($i) => $i->product->price * $i->quantity);
        $shippingFee = (float) Setting::get('shipping_fee', 30);
        $freeMin     = (float) Setting::get('free_shipping_min', 500);
        $vatPercent  = (float) Setting::get('vat_percent', 15);
        $vatEnabled  = (bool)  Setting::get('vat_enabled', 1);

        if ($subtotal >= $freeMin) {
            $shippingFee = 0;
        }

        $discount = ($coupon && $coupon->isValid($subtotal))
            ? $coupon->calculateDiscount($subtotal)
            : 0;

        // الضريبة تُحسب على المبلغ بعد الخصم
        $taxAmount = $vatEnabled ? round(($subtotal - $discount) * ($vatPercent / 100), 2) : 0;
        $total     = max(0, $subtotal - $discount + $shippingFee + $taxAmount);

        return compact(
            'subtotal', 'shippingFee', 'freeMin', 'vatPercent',
            'vatEnabled', 'discount', 'taxAmount', 'total'
        );
    }
}
