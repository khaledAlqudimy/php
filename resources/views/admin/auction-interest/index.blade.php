{{-- ════ admin/auction-interest/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','اهتمامات المزادات')
@section('page-title','اهتمامات المزادات')

@section('content')
<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
            @foreach(['المزاد','الاسم','الهاتف','البريد','الميزانية القصوى','ملاحظات','التاريخ']) as $h)
                <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
            @endforeach
        </tr></thead>
        <tbody>
            @forelse($interests as $i)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:.875rem 1rem;">
                        <div style="font-weight:600;color:var(--brown);font-size:.85rem;">{{ Str::limit($i->auctionItem?->product?->title, 30) }}</div>
                        <div style="font-size:.72rem;color:var(--muted);">{{ $i->auctionItem?->estimated_price ? number_format($i->auctionItem->estimated_price,0).' ر.س تقديري' : '' }}</div>
                    </td>
                    <td style="padding:.875rem 1rem;font-weight:600;color:var(--brown);">{{ $i->name }}</td>
                    <td style="padding:.875rem 1rem;font-size:.825rem;"><a href="tel:{{ $i->phone }}" style="color:var(--gold);">{{ $i->phone }}</a></td>
                    <td style="padding:.875rem 1rem;font-size:.825rem;"><a href="mailto:{{ $i->email }}" style="color:var(--gold);">{{ $i->email }}</a></td>
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ number_format($i->max_budget,0) }} ر.س</td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.8rem;max-width:180px;">{{ Str::limit($i->notes, 50) ?: '—' }}</td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">{{ $i->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد اهتمامات بعد</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);font-size:.8rem;color:var(--muted);">{{ $interests->total() }} إجمالاً</div>
</div>
@endsection


{{-- ════════════════════════════════════════
   admin/personal-shopper/index.blade.php
════════════════════════════════════════ --}}
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
        <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
            @foreach(['نوع القطعة','الحقبة','الميزانية','الوصف','العميل','الحالة','التاريخ','']) as $h)
                <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
            @endforeach
        </tr></thead>
        <tbody>
            @forelse($requests as $req)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ $req->item_type }}</td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.825rem;">{{ $req->era ?: '—' }}</td>
                    <td style="padding:.875rem 1rem;font-weight:700;color:var(--brown);">{{ $req->budget ? number_format($req->budget,0).' ر.س' : 'مفتوح' }}</td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.8rem;max-width:200px;">{{ Str::limit($req->description, 60) }}</td>
                    <td style="padding:.875rem 1rem;font-size:.825rem;color:var(--brown);">{{ $req->user?->name ?? 'زائر' }}</td>
                    <td style="padding:.875rem 1rem;">
                        @php $colors=['NEW'=>['#DBEAFE','#1E40AF'],'IN_PROGRESS'=>['#FEF9C3','#854D0E'],'FULFILLED'=>['#DCFCE7','#166534'],'CLOSED'=>['#F3F4F6','#374151']]; $c=$colors[$req->status]??['#F3F4F6','#374151']; @endphp
                        <span style="padding:.2rem .65rem;border-radius:100px;font-size:.72rem;font-weight:700;background:{{ $c[0] }};color:{{ $c[1] }};">{{ $req->statusLabel() }}</span>
                    </td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);">{{ $req->created_at->format('d/m/Y') }}</td>
                    <td style="padding:.875rem 1rem;">
                        <form action="{{ route('admin.personal-shopper.status', $req->id) }}" method="POST" style="display:flex;gap:.35rem;">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                style="padding:.3rem .6rem;border:1px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.75rem;background:var(--bg);cursor:pointer;">
                                @foreach(['NEW'=>'جديد','IN_PROGRESS'=>'قيد التنفيذ','FULFILLED'=>'منجز','CLOSED'=>'مغلق'] as $v=>$l)
                                    <option value="{{ $v }}" {{ $req->status===$v?'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد طلبات</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);font-size:.8rem;color:var(--muted);">
        {{ $requests->total() }} طلب إجمالاً &nbsp;·&nbsp; {{ $requests->links() }}
    </div>
</div>
@endsection
