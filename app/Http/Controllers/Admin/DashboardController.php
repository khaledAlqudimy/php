<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\AuctionInterest;
use App\Models\PersonalShopperRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── KPIs الرئيسية ───
        $stats = [
            'products'       => Product::count(),
            'active_products'=> Product::where('is_active', true)->count(),
            'orders'         => Order::count(),
            'pending_orders' => Order::where('status', 'PENDING')->count(),
            'customers'      => User::where('role', 'CUSTOMER')->count(),
            'revenue'        => Order::whereIn('status', ['DELIVERED', 'SHIPPED'])->sum('total'),
            'auction_interests' => AuctionInterest::count(),
            'shopper_requests'  => PersonalShopperRequest::where('status', 'NEW')->count(),
        ];

        // ─── آخر 5 طلبات ───
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // ─── إيرادات آخر 7 أيام ───
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->whereIn('status', ['DELIVERED', 'SHIPPED', 'PROCESSING'])
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // ─── توزيع حالات الطلبات ───
        $orderStatuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // ─── أكثر المنتجات مشاهدة ───
        $topProducts = Product::where('is_active', true)
            ->with('images')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats', 'recentOrders', 'revenueChart',
            'orderStatuses', 'topProducts'
        ));
    }
}
