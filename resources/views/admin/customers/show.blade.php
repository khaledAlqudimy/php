{{-- ════ admin/customers/show.blade.php ════ --}}
@extends('layouts.admin')
@section('title','بيانات العميل')
@section('page-title','العميل: ' . $user->name)

@section('content')
<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.customers.index') }}" style="color:var(--muted);font-size:.85rem;">
        <i class="fa-solid fa-arrow-right fa-xs"></i> العودة لقائمة العملاء
    </a>
</div>

<div class="customer-grid" style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;align-items:start;">

    {{-- بيانات العميل --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
            <div style="padding:1.5rem 1.25rem;text-align:center;border-bottom:1px solid var(--border);">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--gold);color:var(--brown);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.5rem;margin:0 auto .75rem;">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div style="font-weight:700;color:var(--brown);">{{ $user->name }}</div>
                <span style="display:inline-block;margin-top:.4rem;padding:.2rem .65rem;border-radius:100px;font-size:.72rem;font-weight:700;background:{{ $user->is_active?'#DCFCE7':'#FEE2E2' }};color:{{ $user->is_active?'#166534':'#991B1B' }};">
                    {{ $user->is_active ? 'نشط' : 'موقوف' }}
                </span>
            </div>
            <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;font-size:.85rem;">
                @foreach([['fa-envelope','البريد',$user->email],['fa-phone','الجوال',$user->phone ?: '—'],['fa-receipt','عدد الطلبات',$user->orders_count],['fa-calendar','تاريخ التسجيل',$user->created_at?->format('d/m/Y')]] as [$icon,$label,$value])
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <i class="fa-solid {{ $icon }}" style="color:var(--gold);margin-top:.2rem;width:14px;"></i>
                        <div><span style="color:var(--muted);display:block;font-size:.72rem;">{{ $label }}</span><span style="color:var(--brown);font-weight:600;">{{ $value }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">العناوين</div>
            <div style="padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.75rem;font-size:.825rem;">
                @forelse($user->addresses as $address)
                    <div style="color:var(--text);">
                        <div style="font-weight:600;color:var(--brown);">{{ $address->full_name }} @if($address->is_default)<span style="color:var(--gold);font-size:.72rem;">(افتراضي)</span>@endif</div>
                        <div style="color:var(--muted);">{{ collect([$address->street, $address->district, $address->city, $address->country])->filter()->join('، ') }}</div>
                        <div style="color:var(--muted);">{{ $address->phone }}</div>
                    </div>
                @empty
                    <div style="color:var(--muted);">لا توجد عناوين</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- آخر الطلبات --}}
    <div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);">
            <i class="fa-solid fa-receipt" style="color:var(--gold);margin-left:.4rem;"></i> آخر الطلبات
        </div>
        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                @foreach(['رقم الطلب','القطع','الإجمالي','الحالة','التاريخ'] as $h)
                    <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:.875rem 1rem;"><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:700;color:var(--gold);">{{ $order->order_number }}</a></td>
                        <td style="padding:.875rem 1rem;color:var(--muted);">{{ $order->items->sum('quantity') }}</td>
                        <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ number_format($order->total, 0) }} ر.س</td>
                        <td style="padding:.875rem 1rem;">{{ $order->statusLabel() }}</td>
                        <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
    @media (max-width: 900px) { .customer-grid { grid-template-columns: 1fr !important; } }
</style>
@endpush
@endsection
