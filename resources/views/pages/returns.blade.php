@extends('layouts.app')
@section('title','الإرجاع والاستبدال')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">سياسة الإرجاع</h1>
        <p style="color:rgba(255,255,255,.65);">14 يوماً لإرجاع أي قطعة بدون أسئلة</p>
    </div>
</div>

<div style="padding:4rem 0;">
    <div class="container" style="max-width:860px;">
        <div style="background:#DCFCE7;border-radius:var(--radius-lg);padding:1.5rem;border:1px solid #BBF7D0;margin-bottom:2rem;display:flex;gap:1rem;align-items:center;">
            <i class="fa-solid fa-circle-check fa-2x" style="color:#166534;flex-shrink:0;"></i>
            <div>
                <div style="font-weight:700;color:#166534;margin-bottom:.2rem;">ضمان رضا العملاء</div>
                <div style="color:#166534;font-size:.875rem;opacity:.9;">إذا لم تكن راضياً عن قطعتك لأي سبب، يمكنك إرجاعها خلال 14 يوماً من تاريخ الاستلام.</div>
            </div>
        </div>

        @foreach([
            ['شروط الإرجاع',['القطعة في حالتها الأصلية دون أي تغيير','لم تتعرض لأي تلف أو خدش إضافي','مع الفاتورة وجميع الملحقات الأصلية','خلال 14 يوماً من تاريخ الاستلام فقط']],
            ['خطوات الإرجاع',['تواصل معنا عبر البريد الإلكتروني أو واتساب','أخبرنا بسبب الإرجاع وأرسل صور للقطعة','سنرسل لك تعليمات التغليف وعنوان الإرجاع','نستلم القطعة ونعيد المبلغ كاملاً خلال 5 أيام عمل']],
        ] as [$title, $items])
            <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--color-border);margin-bottom:1rem;">
                <h3 style="font-weight:700;color:var(--color-brown);margin-bottom:1rem;">{{ $title }}</h3>
                @foreach($items as $i => $item)
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.6rem 0;{{ !$loop->last?'border-bottom:1px solid var(--color-border);':'' }}">
                        <span style="width:24px;height:24px;background:var(--color-gold);color:var(--color-brown);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">{{ $i+1 }}</span>
                        <span style="font-size:.875rem;color:var(--color-text-muted);">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div style="background:rgba(182,138,74,.06);border:1px solid rgba(182,138,74,.2);border-radius:var(--radius-lg);padding:1.5rem;margin-top:1.5rem;">
            <h3 style="font-weight:700;color:var(--color-brown);margin-bottom:.5rem;">
                <i class="fa-solid fa-triangle-exclamation" style="color:var(--color-gold);margin-left:.4rem;"></i>
                حالات لا يُقبل فيها الإرجاع
            </h3>
            <p style="font-size:.875rem;color:var(--color-text-muted);line-height:1.8;">القطع التي تعرضت لتلف بعد الاستلام، أو تجاوزت مدة الـ 14 يوماً، أو تلك المباعة بوضوح "كما هي" مع إشارة صريحة في الصفحة.</p>
        </div>
    </div>
</div>
@endsection
