@extends('layouts.admin')
@section('title','الباحث الشخصي')
@section('page-title','طلبات الباحث الشخصي')

@section('content')
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    @foreach([''=>'الكل','NEW'=>'جديد','IN_PROGRESS'=>'قيد التنفيذ','FULFILLED'=>'منجز','CLOSED'=>'مغلق'] as $val=>$label)
        <a href="{{ route('admin.personal-shopper.index', $val ? ['status'=>$val] : []) }}"
           style="padding:.4rem .9rem;border-radius:100px;border:1.5px solid {{ request('status')===$val?'var(--gold)':'var(--border)' }};background:{{ request('status')===$val?'rgba(182,138,74,.1)':'var(--white)' }};color:{{ request('status')===$val?'var(--gold)':'var(--muted)' }};font-size:.8rem;font-weight:600;text-decoration:none;">
            {{ $label }}
        </a>
    @endforeach
</div>

<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead>
            <tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
                @foreach(['نوع القطعة','الحقبة','الميزانية','الوصف','العميل','الحالة','التاريخ','تغيير الحالة'] as $h)
                    <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
                @php
                    $statusColors = [
                        'NEW'         => ['#DBEAFE','#1E40AF'],
                        'IN_PROGRESS' => ['#FEF9C3','#854D0E'],
                        'FULFILLED'   => ['#DCFCE7','#166534'],
                        'CLOSED'      => ['#F3F4F6','#374151'],
                    ];
                    $c = $statusColors[$req->status] ?? ['#F3F4F6','#374151'];
                @endphp
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ $req->item_type }}</td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $req->era ?: '—' }}</td>
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">
                        {{ $req->budget ? number_format($req->budget, 0).' ر.س' : 'مفتوحة' }}
                    </td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.8rem;max-width:200px;">
                        {{ Str::limit($req->description, 60) }}
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <div style="font-weight:600;font-size:.85rem;color:var(--brown);">{{ $req->user?->name ?? 'زائر' }}</div>
                        @if($req->user)
                            <div style="font-size:.72rem;color:var(--muted);">{{ $req->user->email }}</div>
                        @endif
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <span style="padding:.2rem .65rem;border-radius:100px;font-size:.72rem;font-weight:700;background:{{ $c[0] }};color:{{ $c[1] }};">
                            {{ $req->statusLabel() }}
                        </span>
                    </td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">
                        {{ $req->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding:.875rem 1rem;">
                        <form action="{{ route('admin.personal-shopper.status', $req->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                style="padding:.35rem .6rem;border:1px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.75rem;background:var(--bg);cursor:pointer;">
                                @foreach(['NEW'=>'جديد','IN_PROGRESS'=>'قيد التنفيذ','FULFILLED'=>'منجز','CLOSED'=>'مغلق'] as $v=>$l)
                                    <option value="{{ $v }}" {{ $req->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted);">
                        <i class="fa-solid fa-magnifying-glass fa-2x" style="opacity:.15;margin-bottom:.75rem;display:block;"></i>
                        لا توجد طلبات
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:var(--muted);">
        <span>{{ $requests->total() }} طلب إجمالاً</span>
        {{ $requests->links() }}
    </div>
</div>
@endsection
