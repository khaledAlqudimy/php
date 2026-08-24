{{-- ════ admin/customers/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','العملاء')
@section('page-title','إدارة العملاء')

@section('content')
<div style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('admin.customers.index') }}" style="display:flex;gap:.5rem;">
        <input type="text" name="q" placeholder="بحث بالاسم أو البريد..." value="{{ request('q') }}"
            style="flex:1;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--white);">
        <button type="submit" style="padding:.6rem 1rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:600;cursor:pointer;font-size:.85rem;">بحث</button>
    </form>
</div>

<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
            @foreach(['العميل','الجوال','عدد الطلبات','الحالة','تاريخ التسجيل']) as $h)
                <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
            @endforeach
        </tr></thead>
        <tbody>
            @forelse($customers as $c)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:.875rem 1rem;">
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--gold);color:var(--brown);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.875rem;flex-shrink:0;">
                                {{ mb_substr($c->name,0,1) }}
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--brown);">{{ $c->name }}</div>
                                <div style="font-size:.75rem;color:var(--muted);">{{ $c->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $c->phone ?: '—' }}</td>
                    <td style="padding:.875rem 1rem;">
                        <span style="font-weight:700;color:var(--brown);">{{ $c->orders_count }}</span>
                        <span style="color:var(--muted);font-size:.8rem;"> طلب</span>
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <span style="padding:.2rem .65rem;border-radius:100px;font-size:.72rem;font-weight:700;background:{{ $c->is_active?'#DCFCE7':'#FEE2E2' }};color:{{ $c->is_active?'#166534':'#991B1B' }};">
                            {{ $c->is_active ? 'نشط' : 'موقوف' }}
                        </span>
                    </td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">{{ $c->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:3rem;color:var(--muted);">لا يوجد عملاء</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:var(--muted);">
        <span>{{ $customers->total() }} عميل إجمالاً</span>
        {{ $customers->links() }}
    </div>
</div>
@endsection


{{-- ════ admin/reports/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','التقارير')
@section('page-title','التقارير والإحصائيات')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

    {{-- الإيرادات الشهرية --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-chart-line" style="color:var(--gold);margin-left:.4rem;"></i> الإيرادات الشهرية (آخر 12 شهر)
        </div>
        <div style="padding:1.25rem;">
            @php $maxRevenue = $monthlyRevenue->max('total') ?: 1; @endphp
            @forelse($monthlyRevenue as $month)
                @php
                    $months = ['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
                    $label  = $months[$month->m] . ' ' . $month->y;
                    $pct    = ($month->total / $maxRevenue) * 100;
                @endphp
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                    <span style="width:80px;font-size:.78rem;color:var(--muted);flex-shrink:0;">{{ $label }}</span>
                    <div style="flex:1;height:24px;background:#F5F0EB;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--brown),var(--gold));border-radius:4px;display:flex;align-items:center;justify-content:flex-end;padding-left:.4rem;">
                        </div>
                    </div>
                    <span style="width:80px;text-align:left;font-size:.78rem;font-weight:700;color:var(--brown);flex-shrink:0;">{{ number_format($month->total,0) }} ر.س</span>
                </div>
            @empty
                <div style="text-align:center;padding:2rem;color:var(--muted);font-size:.875rem;">لا توجد بيانات بعد</div>
            @endforelse
        </div>
    </div>

    {{-- أكثر المنتجات مبيعاً --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-trophy" style="color:var(--gold);margin-left:.4rem;"></i> أكثر المنتجات مبيعاً
        </div>
        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;color:var(--muted);">#</th>
                <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;color:var(--muted);">المنتج</th>
                <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">المبيع</th>
                <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">الإيراد</th>
            </tr></thead>
            <tbody>
                @forelse($topSellingProducts as $i => $item)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:.7rem 1rem;">
                            <span style="width:24px;height:24px;border-radius:50%;background:{{ $i<3?'var(--gold)':'var(--bg)' }};color:{{ $i<3?'var(--brown)':'var(--muted)' }};display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;">{{ $i+1 }}</span>
                        </td>
                        <td style="padding:.7rem 1rem;font-weight:600;color:var(--brown);font-size:.825rem;">{{ Str::limit($item->product?->title, 30) }}</td>
                        <td style="padding:.7rem 1rem;text-align:center;font-weight:700;color:var(--brown);">{{ $item->sold }}</td>
                        <td style="padding:.7rem 1rem;text-align:center;color:var(--muted);font-size:.8rem;">{{ number_format($item->revenue,0) }} ر.س</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--muted);">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
