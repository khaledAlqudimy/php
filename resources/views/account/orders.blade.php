@extends('layouts.app')
@section('title', 'طلباتي')

@push('styles')
<style>
.account-page { padding: 2.5rem 0 5rem; }
.account-header { background: var(--color-brown); padding: 3rem 0 2.5rem; }
.account-header-title { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); }

.account-nav { display: flex; gap: .25rem; overflow-x: auto; margin: 2rem 0 2.5rem; padding-bottom: .25rem; }
.account-nav-link { display:flex;align-items:center;gap:.4rem;padding:.6rem 1.1rem;border-radius:var(--radius);font-size:.875rem;font-weight:600;color:var(--color-text-muted);white-space:nowrap;transition:var(--transition);text-decoration:none; }
.account-nav-link:hover { color:var(--color-brown);background:var(--color-bg-2); }
.account-nav-link.active { background:var(--color-brown);color:var(--color-gold); }

/* ── Order Cards ── */
.orders-list { display: flex; flex-direction: column; gap: 1.25rem; }

.order-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
    transition: var(--transition);
}
.order-card:hover { box-shadow: var(--shadow-md); }

.order-head {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
    padding: 1.1rem 1.5rem;
    background: var(--color-bg);
    border-bottom: 1px solid var(--color-border);
}

.order-num { font-weight: 700; color: var(--color-brown); font-size: .9rem; }
.order-num span { color: var(--color-gold); font-family: monospace; }
.order-date { font-size: .78rem; color: var(--color-text-muted); margin-top: .15rem; }

.status-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .9rem;
    border-radius: 100px;
    font-size: .75rem; font-weight: 700;
}

.status-PENDING    { background: #FEF9C3; color: #854D0E; }
.status-PROCESSING { background: #DBEAFE; color: #1E40AF; }
.status-SHIPPED    { background: #E0E7FF; color: #3730A3; }
.status-DELIVERED  { background: #DCFCE7; color: #166534; }
.status-CANCELLED  { background: #FEE2E2; color: #991B1B; }
.status-REFUNDED   { background: #F3F4F6; color: #374151; }

/* Order Items */
.order-items { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .75rem; }

.order-item {
    display: flex; align-items: center; gap: 1rem;
    padding: .75rem;
    border-radius: var(--radius);
    border: 1px solid var(--color-border);
    background: var(--color-bg);
}

.order-item-img {
    width: 56px; height: 56px;
    border-radius: var(--radius);
    overflow: hidden; flex-shrink: 0;
    border: 1px solid var(--color-border);
    background: var(--color-bg-2);
}
.order-item-img img { width: 100%; height: 100%; object-fit: cover; }

.order-item-name { font-weight: 600; font-size: .875rem; color: var(--color-brown); }
.order-item-meta { font-size: .75rem; color: var(--color-text-muted); margin-top: .15rem; }
.order-item-price { margin-right: auto; font-weight: 700; color: var(--color-brown); font-size: .875rem; white-space: nowrap; }

/* Order Footer */
.order-foot {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--color-border);
    background: var(--color-white);
}

.order-total-label { font-size: .8rem; color: var(--color-text-muted); }
.order-total-amount { font-size: 1.1rem; font-weight: 700; color: var(--color-brown); }

.order-address {
    font-size: .78rem; color: var(--color-text-muted);
    display: flex; align-items: center; gap: .35rem;
}

/* Empty */
.orders-empty {
    text-align: center; padding: 5rem 2rem;
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    color: var(--color-text-muted);
}
.orders-empty i { font-size: 3.5rem; opacity: .15; margin-bottom: 1.25rem; }
.orders-empty h3 { font-size: 1.1rem; color: var(--color-brown); margin-bottom: .5rem; }
</style>
@endpush

@section('content')

<div class="account-header">
    <div class="container">
        <h1 class="account-header-title">حسابي</h1>
    </div>
</div>

<div class="account-page">
    <div class="container">

        <nav class="account-nav">
            <a href="{{ route('account.profile') }}"   class="account-nav-link {{ request()->routeIs('account.profile')   ? 'active' : '' }}"><i class="fa-regular fa-circle-user fa-sm"></i> الملف الشخصي</a>
            <a href="{{ route('account.orders') }}"    class="account-nav-link {{ request()->routeIs('account.orders')    ? 'active' : '' }}"><i class="fa-solid fa-receipt fa-sm"></i> طلباتي</a>
            <a href="{{ route('account.wishlist') }}"  class="account-nav-link {{ request()->routeIs('account.wishlist')  ? 'active' : '' }}"><i class="fa-regular fa-heart fa-sm"></i> المفضلة</a>
            <a href="{{ route('account.addresses') }}" class="account-nav-link {{ request()->routeIs('account.addresses') ? 'active' : '' }}"><i class="fa-solid fa-location-dot fa-sm"></i> عناوين التوصيل</a>
        </nav>

        <div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--color-brown);">سجل الطلبات</h2>
                <p style="font-size:.85rem;color:var(--color-text-muted);margin-top:.2rem;">{{ $orders->total() }} طلب</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-primary" style="font-size:.875rem;padding:.6rem 1.25rem;">
                <i class="fa-solid fa-plus fa-sm"></i> طلب جديد
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="orders-empty">
                <i class="fa-solid fa-receipt"></i>
                <h3>لا توجد طلبات بعد</h3>
                <p>ابدأ تسوقك واكتشف قطعاً رائعة من مجموعتنا</p>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top:1rem;">
                    <i class="fa-solid fa-arrow-left fa-sm"></i> تصفح المتجر
                </a>
            </div>
        @else
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-card">

                        {{-- Header --}}
                        <div class="order-head">
                            <div>
                                <div class="order-num">
                                    طلب رقم: <span>{{ $order->order_number }}</span>
                                </div>
                                <div class="order-date">
                                    <i class="fa-regular fa-calendar fa-xs"></i>
                                    {{ $order->created_at->format('d/m/Y — H:i') }}
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <span class="status-badge status-{{ $order->status }}">
                                    @php
                                        $icons = ['PENDING'=>'fa-clock','PROCESSING'=>'fa-gear','SHIPPED'=>'fa-truck-fast','DELIVERED'=>'fa-check-circle','CANCELLED'=>'fa-ban','REFUNDED'=>'fa-rotate-left'];
                                    @endphp
                                    <i class="fa-solid {{ $icons[$order->status] ?? 'fa-circle' }} fa-xs"></i>
                                    {{ $order->statusLabel() }}
                                </span>
                            </div>
                        </div>

                        {{-- Items (max 3 visible) --}}
                        <div class="order-items">
                            @foreach($order->items->take(3) as $item)
                                <div class="order-item">
                                    <div class="order-item-img">
                                        @if($item->product && $item->product->mainImage())
                                            <img src="{{ Storage::url($item->product->mainImage()) }}" alt="{{ $item->title }}">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:1.25rem;opacity:.3;">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="order-item-name">{{ $item->title }}</div>
                                        <div class="order-item-meta">الكمية: {{ $item->quantity }} × {{ number_format($item->price, 0) }} ر.س</div>
                                    </div>
                                    <div class="order-item-price">{{ number_format($item->price * $item->quantity, 0) }} ر.س</div>
                                </div>
                            @endforeach

                            @if($order->items->count() > 3)
                                <div style="text-align:center;font-size:.8rem;color:var(--color-text-muted);padding:.5rem 0;">
                                    + {{ $order->items->count() - 3 }} قطعة أخرى
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="order-foot">
                            <div>
                                @if($order->address)
                                    <div class="order-address">
                                        <i class="fa-solid fa-location-dot fa-xs" style="color:var(--color-gold);"></i>
                                        {{ $order->address->city }}، {{ $order->address->country }}
                                    </div>
                                @endif
                                <div style="font-size:.78rem;color:var(--color-text-muted);margin-top:.2rem;">
                                    <i class="fa-solid fa-credit-card fa-xs"></i>
                                    @php
                                        $payLabels = ['cod'=>'الدفع عند الاستلام','bank_transfer'=>'تحويل بنكي','online'=>'بطاقة ائتمانية'];
                                    @endphp
                                    {{ $payLabels[$order->payment_method] ?? $order->payment_method }}
                                </div>
                            </div>
                            <div style="text-align:left;">
                                <div class="order-total-label">الإجمالي</div>
                                <div class="order-total-amount">{{ number_format($order->total, 0) }} ر.س</div>
                                @if($order->discount > 0)
                                    <div style="font-size:.75rem;color:#166534;">وفّرت {{ number_format($order->discount, 0) }} ر.س</div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div style="margin-top:2rem;display:flex;justify-content:center;">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
