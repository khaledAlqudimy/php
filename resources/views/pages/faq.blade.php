@extends('layouts.app')
@section('title','الأسئلة الشائعة')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">الأسئلة الشائعة</h1>
        <p style="color:rgba(255,255,255,.65);">إجابات على أكثر الأسئلة تكراراً</p>
    </div>
</div>

<div style="padding:4rem 0;">
    <div class="container" style="max-width:760px;">
        @php
        $faqs = [
            ['كيف أتأكد من أصالة القطعة؟','كل قطعة في معرضنا تمر بفحص دقيق من فريق خبرائنا المتخصصين. نقدم شهادة أصالة مع كل قطعة تتضمن تقريراً مفصلاً عن تاريخها ومصدرها وحالتها.'],
            ['هل يمكنني إرجاع القطعة إذا لم أكن راضياً؟','نعم، لدينا سياسة إرجاع مرنة تتيح لك إرجاع القطعة خلال 14 يوماً من تاريخ الاستلام بدون أسئلة، شريطة أن تكون في حالتها الأصلية.'],
            ['كيف تتم عملية الشحن؟','نستخدم مواد تغليف متخصصة وشركات شحن موثوقة. مدة التوصيل 3-7 أيام عمل داخل المملكة. ستصلك رسالة تتبع فور شحن طلبك.'],
            ['هل يمكنني طلب قطعة بعينها؟','بالتأكيد! خدمة الباحث الشخصي مصممة خصيصاً لهذا. أخبرنا عن القطعة التي تبحث عنها وسيعمل فريقنا على إيجادها من شبكة مصادرنا العالمية.'],
            ['ما هي طرق الدفع المتاحة؟','نقبل: الدفع عند الاستلام والتحويل البنكي. نعمل على إضافة خيارات دفع إلكترونية إضافية قريباً.'],
            ['كيف أشارك في المزادات؟','تصفح صفحة المزادات وسجّل اهتمامك بالقطعة التي تريدها. سيتواصل معك فريقنا قبل موعد المزاد بجميع التفاصيل.'],
            ['هل تشحنون خارج المملكة؟','نوصّل حالياً داخل المملكة. للشحن الدولي تواصل معنا مباشرة وسنرتب لك ذلك بسعر مناسب.'],
        ];
        @endphp

        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach($faqs as $i => $faq)
                <div style="background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden;transition:border-color .2s;" id="faq-{{ $i }}">
                    <button onclick="toggleFaq({{ $i }})"
                        style="width:100%;padding:1.25rem 1.5rem;background:none;border:none;font-family:var(--font-main);font-size:.925rem;font-weight:700;color:var(--color-brown);text-align:right;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        <span>{{ $faq[0] }}</span>
                        <i class="fa-solid fa-chevron-down fa-sm" id="icon-{{ $i }}" style="color:var(--color-gold);transition:transform .3s;flex-shrink:0;"></i>
                    </button>
                    <div id="body-{{ $i }}" style="display:none;padding:.25rem 1.5rem 1.25rem;color:var(--color-text-muted);font-size:.875rem;line-height:1.9;border-top:1px solid var(--color-border);">
                        {{ $faq[1] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:3rem;padding:2rem;background:var(--color-bg-2);border-radius:var(--radius-lg);border:1px solid var(--color-border);">
            <p style="color:var(--color-text-muted);margin-bottom:1rem;font-size:.9rem;">لم تجد إجابة على سؤالك؟</p>
            <a href="{{ route('contact') }}" class="btn btn-primary">
                <i class="fa-solid fa-envelope fa-sm"></i> تواصل معنا
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFaq(i) {
    const body = document.getElementById('body-' + i);
    const icon = document.getElementById('icon-' + i);
    const isOpen = body.style.display !== 'none';
    document.querySelectorAll('[id^="body-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('[id^="icon-"]').forEach(el => el.style.transform = 'rotate(0)');
    if (!isOpen) {
        body.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
@endpush
@endsection
