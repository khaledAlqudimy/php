@extends('layouts.admin')
@section('title','التقارير')
@section('page-title','التقارير والإحصائيات')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

    {{-- الإيرادات الشهرية --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-chart-line" style="color:var(--gold);margin-left:.4rem;"></i> الإيرادات الشهرية (آخر 12 شهر)
        </div>
        <div style="padding:1.25rem;">
            @php
                $months = ['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
                $maxRevenue = $monthlyRevenue->max('total') ?: 1;
            @endphp
            @forelse($monthlyRevenue as $month)
                @php
                    $label = $months[$month->m] . ' ' . $month->y;
                    $pct   = ($month->total / $maxRevenue) * 100;
                @endphp
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                    <span style="width:90px;font-size:.75rem;color:var(--muted);flex-shrink:0;text-align:right;">{{ $label }}</span>
                    <div style="flex:1;height:22px;background:#F5F0EB;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--brown),var(--gold));border-radius:4px;"></div>
                    </div>
                    <span style="width:90px;font-size:.75rem;font-weight:700;color:var(--brown);flex-shrink:0;">
                        {{ number_format($month->total, 0) }} ر.س
                    </span>
                </div>
            @empty
                <div style="text-align:center;padding:2rem;color:var(--muted);">لا توجد بيانات بعد</div>
            @endforelse
        </div>
    </div>

    {{-- أكثر المنتجات مبيعاً --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-trophy" style="color:var(--gold);margin-left:.4rem;"></i> أكثر المنتجات مبيعاً
        </div>
        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <thead>
                <tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                    <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;color:var(--muted);">#</th>
                    <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;color:var(--muted);">المنتج</th>
                    <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">الكمية المبيعة</th>
                    <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;color:var(--muted);">الإيراد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topSellingProducts as $i => $item)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:.75rem 1rem;">
                            <span style="width:26px;height:26px;border-radius:50%;background:{{ $i<3?'var(--gold)':'var(--bg-2)' }};color:{{ $i<3?'var(--brown)':'var(--muted)' }};display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td style="padding:.75rem 1rem;font-weight:600;color:var(--brown);font-size:.85rem;">
                            {{ Str::limit($item->product?->title, 32) }}
                        </td>
                        <td style="padding:.75rem 1rem;text-align:center;font-weight:700;color:var(--brown);">
                            {{ number_format($item->sold) }}
                        </td>
                        <td style="padding:.75rem 1rem;text-align:center;color:var(--muted);font-size:.825rem;">
                            {{ number_format($item->revenue, 0) }} ر.س
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:2rem;color:var(--muted);">لا توجد بيانات مبيعات بعد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
