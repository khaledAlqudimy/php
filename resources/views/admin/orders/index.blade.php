{{-- admin/orders/index.blade.php --}}
@extends('layouts.admin')
@section('title','الطلبات')
@section('page-title','إدارة الطلبات')

@section('content')
<div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:.5rem;flex:1;min-width:240px;">
        <input type="text" name="q" placeholder="رقم الطلب أو اسم العميل..."
            value="{{ request('q') }}"
            style="flex:1;padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;background:var(--white);">
        <select name="status" onchange="this.form.submit()"
            style="padding:.6rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.85rem;background:var(--white);cursor:pointer;">
            <option value="">كل الحالات</option>
            @foreach(['PENDING'=>'قيد الانتظار','PROCESSING'=>'قيد المعالجة','SHIPPED'=>'تم الشحن','DELIVERED'=>'تم التوصيل','CANCELLED'=>'ملغي','REFUNDED'=>'مسترجع'] as $val=>$label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" style="padding:.6rem 1rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-weight:600;cursor:pointer;font-size:.85rem;">بحث</button>
    </form>
</div>

<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead>
            <tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                @foreach(['رقم الطلب','العميل','القطع','الإجمالي','طريقة الدفع','الحالة','التاريخ',''] as $h)
                    <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:.875rem 1rem;">
                        <span style="font-family:monospace;font-weight:700;color:var(--gold);font-size:.8rem;">{{ $order->order_number }}</span>
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <div style="font-weight:600;font-size:.85rem;color:var(--brown);">{{ $order->user?->name }}</div>
                        <div style="font-size:.75rem;color:var(--muted);">{{ $order->user?->email }}</div>
                    </td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $order->items->count() }} قطعة</td>
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ number_format($order->total,0) }} ر.س</td>
                    <td style="padding:.875rem 1rem;font-size:.8rem;color:var(--muted);">
                        {{ ['cod'=>'الاستلام','bank_transfer'=>'تحويل','online'=>'إلكتروني'][$order->payment_method] ?? $order->payment_method }}
                        @if($order->payment_status === 'paid')
                            <span style="color:#166534;font-weight:700;">✓</span>
                        @endif
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <span class="status-pill pill-{{ $order->status }}">{{ $order->statusLabel() }}</span>
                    </td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td style="padding:.875rem 1rem;">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           style="display:inline-flex;align-items:center;gap:.3rem;padding:.4rem .75rem;background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;border-radius:var(--radius);font-size:.78rem;font-weight:600;text-decoration:none;">
                            <i class="fa-solid fa-eye fa-xs"></i> تفاصيل
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد طلبات</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:var(--muted);">
        <span>{{ $orders->total() }} طلب إجمالاً</span>
        {{ $orders->links() }}
    </div>
</div>

@push('styles')
<style>
.status-pill{display:inline-flex;align-items:center;padding:.2rem .7rem;border-radius:100px;font-size:.72rem;font-weight:700;}
.pill-PENDING{background:#FEF9C3;color:#854D0E;}.pill-PROCESSING{background:#DBEAFE;color:#1E40AF;}
.pill-SHIPPED{background:#E0E7FF;color:#3730A3;}.pill-DELIVERED{background:#DCFCE7;color:#166534;}
.pill-CANCELLED{background:#FEE2E2;color:#991B1B;}.pill-REFUNDED{background:#F3F4F6;color:#374151;}
</style>
@endpush
@endsection
