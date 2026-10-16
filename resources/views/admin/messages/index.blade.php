{{-- ════ admin/messages/index.blade.php ════ --}}
@extends('layouts.admin')
@section('title','رسائل التواصل')
@section('page-title','رسائل «تواصل معنا»')

@section('content')
<div style="background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
        <thead><tr style="background:#FAFAF9;border-bottom:1px solid var(--border);">
            @foreach(['المرسل','التواصل','الموضوع','الرسالة','التاريخ'] as $h)
                <th style="padding:.75rem 1rem;text-align:right;font-size:.72rem;font-weight:700;color:var(--muted);">{{ $h }}</th>
            @endforeach
        </tr></thead>
        <tbody>
            @forelse($messages as $m)
                <tr style="border-bottom:1px solid var(--border);{{ $m->is_read ? '' : 'background:rgba(182,138,74,.06);' }}">
                    <td style="padding:.875rem 1rem;font-weight:600;color:var(--brown);">
                        {{ $m->name }}
                        @unless($m->is_read)
                            <span style="padding:.1rem .5rem;border-radius:100px;font-size:.65rem;font-weight:700;background:var(--gold);color:var(--brown);margin-right:.3rem;">جديد</span>
                        @endunless
                    </td>
                    <td style="padding:.875rem 1rem;font-size:.8rem;">
                        <a href="mailto:{{ $m->email }}" style="color:var(--gold);">{{ $m->email }}</a>
                        @if($m->phone)
                            <div><a href="tel:{{ $m->phone }}" style="color:var(--muted);">{{ $m->phone }}</a></div>
                        @endif
                    </td>
                    <td style="padding:.875rem 1rem;font-weight:600;color:var(--brown);font-size:.825rem;">{{ $m->subject }}</td>
                    <td style="padding:.875rem 1rem;color:var(--muted);font-size:.8rem;max-width:360px;white-space:pre-line;">{{ $m->message }}</td>
                    <td style="padding:.875rem 1rem;font-size:.78rem;color:var(--muted);white-space:nowrap;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:3rem;color:var(--muted);">لا توجد رسائل بعد</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:var(--muted);">
        <span>{{ $messages->total() }} رسالة إجمالاً</span>
        {{ $messages->links() }}
    </div>
</div>
@endsection
