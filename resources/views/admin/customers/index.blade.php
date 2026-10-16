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
            @foreach(['العميل','الجوال','عدد الطلبات','الحالة','تاريخ التسجيل'] as $h)
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
                                <a href="{{ route('admin.customers.show', $c) }}" style="font-weight:700;color:var(--brown);">{{ $c->name }}</a>
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
