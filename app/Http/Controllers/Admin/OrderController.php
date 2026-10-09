<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($s = $request->get('q')) {
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.images', 'address', 'coupon']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:PENDING,PROCESSING,SHIPPED,DELIVERED,CANCELLED,REFUNDED'],
        ]);

        $oldStatus = $order->status;

        // الإلغاء/الاسترجاع يعيد القطع للمخزون، وإعادة التفعيل يحجزها من جديد
        DB::transaction(fn () => $order->changeStatus($request->status));

        if ($oldStatus !== $order->status) {
            OrderNotifier::statusChanged($order);
        }

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$order->statusLabel()}");
    }
}
