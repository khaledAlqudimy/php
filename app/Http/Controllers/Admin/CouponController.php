<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('orders')->latest('created_at')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => ['required', 'string', 'max:30', 'unique:coupons,code'],
            'type'        => ['required', 'in:PERCENTAGE,FIXED'],
            'value'       => ['required', 'numeric', 'min:1'],
            'min_order'   => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at'  => ['nullable', 'date', 'after:today'],
        ], [
            'code.unique' => 'هذا الكود مستخدم مسبقاً',
        ]);

        Coupon::create([
            'code'        => strtoupper($request->code),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_order'   => $request->min_order,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'is_active'   => true,
        ]);

        return back()->with('success', 'تم إضافة الكوبون بنجاح.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);
        $label = $coupon->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$label} الكوبون.");
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'تم حذف الكوبون.');
    }
}
