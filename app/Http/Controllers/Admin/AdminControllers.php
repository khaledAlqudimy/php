<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Category, Coupon, User,
                AuctionInterest, PersonalShopperRequest, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// ═══════════════════════════════════════════════════════════════
//  OrderController
// ═══════════════════════════════════════════════════════════════
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($s = $request->get('q')) {
            $query->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"));
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

        $order->update(['status' => $request->status]);

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$order->statusLabel()}");
    }
}

// ═══════════════════════════════════════════════════════════════
//  CategoryController
// ═══════════════════════════════════════════════════════════════
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount('products')
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'order'     => ['integer', 'min:0'],
        ]);

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $request->parent_id ?: null,
            'order'     => $request->order ?? 0,
        ]);

        return back()->with('success', 'تم إضافة التصنيف.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'order' => ['integer', 'min:0'],
        ]);

        $category->update([
            'name'  => $request->name,
            'order' => $request->order ?? $category->order,
        ]);

        return back()->with('success', 'تم تحديث التصنيف.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف تصنيف به منتجات. انقل المنتجات أولاً.');
        }
        $category->delete();
        return back()->with('success', 'تم حذف التصنيف.');
    }
}

// ═══════════════════════════════════════════════════════════════
//  CouponController
// ═══════════════════════════════════════════════════════════════
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

// ═══════════════════════════════════════════════════════════════
//  CustomerController
// ═══════════════════════════════════════════════════════════════
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'CUSTOMER')->withCount('orders')->latest();

        if ($s = $request->get('q')) {
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
        }

        $customers = $query->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        $orders = $user->orders()->with('items')->latest()->take(10)->get();
        return view('admin.customers.show', compact('user', 'orders'));
    }
}

// ═══════════════════════════════════════════════════════════════
//  AuctionInterestController
// ═══════════════════════════════════════════════════════════════
class AuctionInterestController extends Controller
{
    public function index()
    {
        $interests = AuctionInterest::with(['auctionItem.product', 'user'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.auction-interest.index', compact('interests'));
    }
}

// ═══════════════════════════════════════════════════════════════
//  PersonalShopperController
// ═══════════════════════════════════════════════════════════════
class PersonalShopperController extends Controller
{
    public function index(Request $request)
    {
        $query = PersonalShopperRequest::with('user')->latest('created_at');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();
        return view('admin.personal-shopper.index', compact('requests'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:NEW,IN_PROGRESS,FULFILLED,CLOSED'],
        ]);

        PersonalShopperRequest::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث الحالة.');
    }
}

// ═══════════════════════════════════════════════════════════════
//  ReportController
// ═══════════════════════════════════════════════════════════════
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

// ═══════════════════════════════════════════════════════════════
//  SettingController
// ═══════════════════════════════════════════════════════════════
class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $allowed = [
            'site_name', 'site_tagline', 'site_email', 'site_phone',
            'site_address', 'site_whatsapp', 'social_instagram',
            'social_twitter', 'social_facebook', 'social_snapchat',
            'shipping_fee', 'free_shipping_min', 'delivery_days_min',
            'delivery_days_max', 'vat_percent', 'vat_enabled',
            'personal_shopper_enabled', 'auction_enabled',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->get($key));
            }
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
