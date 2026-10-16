{{-- ════ admin/auction-interest/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','اهتمامات المزادات')
@section('page-title','اهتمامات المزادات')

@section('content')
<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
            @foreach(['المزاد','الاسم','الهاتف','البريد','الميزانية القصوى','ملاحظات','التاريخ'] as $h)
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
