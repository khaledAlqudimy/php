<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

        // إلغاء/استرجاع الطلب يعيد القطع للمخزون، وإعادة تفعيله يحجزها من جديد
        $closed     = ['CANCELLED', 'REFUNDED'];
        $wasClosed  = in_array($order->status, $closed);
        $willClose  = in_array($request->status, $closed);

        DB::transaction(function () use ($order, $request, $wasClosed, $willClose) {
            if ($wasClosed !== $willClose) {
                foreach ($order->items()->with('product')->get() as $item) {
                    if (! $item->product) continue;
                    $willClose
                        ? $item->product->increment('stock', $item->quantity)
                        : $item->product->decrement('stock', $item->quantity);
                }
            }

            $order->update(['status' => $request->status]);
        });

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$order->statusLabel()}");
    }
}
