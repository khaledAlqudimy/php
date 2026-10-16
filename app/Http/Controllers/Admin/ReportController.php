<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class ReportController extends Controller
{
    public function index()
    {
        $monthlyRevenue = Order::selectRaw('YEAR(created_at) y, MONTH(created_at) m, SUM(total) total, COUNT(*) cnt')
            ->whereIn('status', ['DELIVERED', 'SHIPPED', 'PROCESSING'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $topSellingProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as sold, SUM(price*quantity) as revenue')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('monthlyRevenue', 'topSellingProducts'));
    }
}
