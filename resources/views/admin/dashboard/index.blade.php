@extends('layouts.admin')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@push('styles')
<style>
/* ── KPI Cards ── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.kpi-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 1.5rem;
    border: 1px solid var(--border);
    display: flex; align-items: flex-start; gap: 1rem;
    transition: box-shadow .2s;
}
.kpi-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }

.kpi-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}
.kpi-icon.brown  { background: rgba(58,36,24,.1);  color: var(--brown); }
.kpi-icon.gold   { background: rgba(182,138,74,.12); color: var(--gold); }
.kpi-icon.green  { background: #DCFCE7; color: #166534; }
.kpi-icon.blue   { background: #DBEAFE; color: #1E40AF; }
.kpi-icon.purple { background: #EDE9FE; color: #7C3AED; }
.kpi-icon.red    { background: #FEE2E2; color: #DC2626; }

.kpi-value { font-size: 1.6rem; font-weight: 700; color: var(--brown); line-height: 1; margin-bottom: .2rem; }
.kpi-label { font-size: .78rem; color: var(--muted); }
.kpi-sub   { font-size: .75rem; color: var(--muted); margin-top: .3rem; }
.kpi-sub span { color: var(--gold); font-weight: 600; }

/* ── Tables ── */
.admin-table-wrap {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
}

.admin-table-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    font-weight: 700; font-size: .875rem; color: var(--brown);
}

.admin-table {
    width: 100%; border-collapse: collapse;
    font-size: .85rem;
}
.admin-table th {
    padding: .75rem 1.25rem;
    text-align: right;
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .5px;
    background: #FAFAF9;
    border-bottom: 1px solid var(--border);
}
.admin-table td {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid var(--border);
    color: var(--text); vertical-align: middle;
}
.admin-table tr:last-child td { border: none; }
.admin-table tr:hover td { background: #FAFAF9; }

/* Order Status Badges */
.status-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .7rem; border-radius: 100px;
    font-size: .72rem; font-weight: 700;
}
.pill-PENDING    { background: #FEF9C3; color: #854D0E; }
.pill-PROCESSING { background: #DBEAFE; color: #1E40AF; }
.pill-SHIPPED    { background: #E0E7FF; color: #3730A3; }
.pill-DELIVERED  { background: #DCFCE7; color: #166534; }
.pill-CANCELLED  { background: #FEE2E2; color: #991B1B; }
.pill-REFUNDED   { background: #F3F4F6; color: #374151; }

/* ── 2-column grid ── */
.dash-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.product-mini {
    display: flex; gap: .75rem; align-items: center;
    padding: .6rem .75rem; border-radius: var(--radius);
}
.product-mini:hover { background: #FAFAF9; }

.product-mini-img {
    width: 40px; height: 40px; border-radius: var(--radius);
    overflow: hidden; flex-shrink: 0; background: #F5F0EB;
    border: 1px solid var(--border);
}
.product-mini-img img { width: 100%; height: 100%; object-fit: cover; }

.product-mini-name { font-size: .825rem; font-weight: 600; color: var(--brown); }
.product-mini-views { font-size: .75rem; color: var(--muted); }
.product-mini-bar {
    height: 4px; background: #EEE;
    border-radius: 100px; margin-top: .3rem; overflow: hidden;
}
.product-mini-fill { height: 100%; background: var(--gold); border-radius: 100px; }

/* Action Buttons */
.btn-admin {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .45rem .9rem;
    border-radius: var(--radius);
    font-family: var(--font); font-size: .8rem; font-weight: 600;
    cursor: pointer; border: none; transition: all .2s; text-decoration: none;
}
.btn-admin-primary { background: var(--brown); color: var(--gold); }
.btn-admin-primary:hover { background: var(--gold); color: var(--brown); }
.btn-admin-outline { background: transparent; border: 1.5px solid var(--border); color: var(--muted); }
.btn-admin-outline:hover { border-color: var(--gold); color: var(--gold); }

@media (max-width: 900px) {
    .dash-cols { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ── KPIs ── --}}
<div class="kpi-grid">
    @php
    $kpis = [
        ['icon'=>'fa-box-open',  'color'=>'brown',  'value'=>number_format($stats['products']),
         'label'=>'إجمالي المنتجات', 'sub'=>$stats['active_products'] . ' نشط'],
        ['icon'=>'fa-receipt',   'color'=>'gold',   'value'=>number_format($stats['orders']),
         'label'=>'إجمالي الطلبات', 'sub'=>$stats['pending_orders'] . ' قيد الانتظار'],
        ['icon'=>'fa-users',     'color'=>'blue',   'value'=>number_format($stats['customers']),
         'label'=>'العملاء المسجّلون', 'sub'=>''],
        ['icon'=>'fa-coins',     'color'=>'green',  'value'=>number_format($stats['revenue'], 0) . ' ر.س',
         'label'=>'الإيرادات المكتملة', 'sub'=>''],
        ['icon'=>'fa-gavel',     'color'=>'purple', 'value'=>number_format($stats['auction_interests']),
         'label'=>'اهتمامات المزادات', 'sub'=>''],
        ['icon'=>'fa-magnifying-glass','color'=>'red', 'value'=>number_format($stats['shopper_requests']),
         'label'=>'طلبات الباحث الجديدة', 'sub'=>''],
    ];
    @endphp
    @foreach($kpis as $kpi)
        <div class="kpi-card">
            <div class="kpi-icon {{ $kpi['color'] }}">
                <i class="fa-solid {{ $kpi['icon'] }}"></i>
            </div>
            <div>
                <div class="kpi-value">{{ $kpi['value'] }}</div>
                <div class="kpi-label">{{ $kpi['label'] }}</div>
                @if($kpi['sub'])
                    <div class="kpi-sub"><span>{{ $kpi['sub'] }}</span></div>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- ── Quick Actions ── --}}
<div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem;">
    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin-primary">
        <i class="fa-solid fa-plus fa-xs"></i> منتج جديد
    </a>
    <a href="{{ route('admin.orders.index', ['status'=>'PENDING']) }}" class="btn-admin btn-admin-outline">
        <i class="fa-solid fa-clock fa-xs"></i> الطلبات المعلّقة ({{ $stats['pending_orders'] }})
    </a>
    <a href="{{ route('admin.personal-shopper.index', ['status'=>'NEW']) }}" class="btn-admin btn-admin-outline">
        <i class="fa-solid fa-magnifying-glass fa-xs"></i> طلبات الباحث الجديدة
    </a>
</div>

{{-- ── 2-col grid ── --}}
<div class="dash-cols">

    {{-- آخر الطلبات --}}
    <div class="admin-table-wrap">
        <div class="admin-table-head">
            <span><i class="fa-solid fa-receipt" style="color:var(--gold);margin-left:.4rem;"></i>آخر الطلبات</span>
            <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-outline">عرض الكل</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               style="color:var(--gold);font-family:monospace;font-weight:700;font-size:.8rem;">
                                {{ $order->order_number }}
                            </a>
                            <div style="font-size:.72rem;color:var(--muted);">{{ $order->created_at->diffForHumans() }}</div>
                        </td>
                        <td style="font-size:.825rem;">{{ $order->user?->name }}</td>
                        <td style="font-weight:700;color:var(--brown);">{{ number_format($order->total, 0) }}</td>
                        <td>
                            <span class="status-pill pill-{{ $order->status }}">
                                {{ $order->statusLabel() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:2rem;">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- أكثر المنتجات مشاهدة --}}
    <div class="admin-table-wrap">
        <div class="admin-table-head">
            <span><i class="fa-solid fa-fire" style="color:var(--gold);margin-left:.4rem;"></i>أكثر المنتجات مشاهدة</span>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-outline">عرض الكل</a>
        </div>
        <div style="padding:.75rem;">
            @php $maxViews = $topProducts->max('views_count') ?: 1; @endphp
            @forelse($topProducts as $product)
                <div class="product-mini">
                    <div class="product-mini-img">
                        @if($product->mainImage())
                            <img src="{{ Storage::url($product->mainImage()) }}" alt="{{ $product->title }}">
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="product-mini-name">{{ Str::limit($product->title, 32) }}</div>
                        <div class="product-mini-views">{{ number_format($product->views_count) }} مشاهدة</div>
                        <div class="product-mini-bar">
                            <div class="product-mini-fill" style="width:{{ ($product->views_count / $maxViews) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;color:var(--muted);padding:2rem;font-size:.875rem;">لا توجد منتجات بعد</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
