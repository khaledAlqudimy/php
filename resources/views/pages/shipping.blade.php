{{-- ════ pages/shipping.blade.php ════ --}}
@extends('layouts.app')
@section('title','الشحن والتوصيل')
@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">الشحن والتوصيل</h1>
        <p style="color:rgba(255,255,255,.65);">نوصل قطعتك بأمان إلى أي مكان</p>
    </div>
</div>

<div style="padding:4rem 0;">
    <div class="container" style="max-width:860px;">
        @php
            $shippingFee = \App\Models\Setting::get('shipping_fee', 30);
            $freeMin     = \App\Models\Setting::get('free_shipping_min', 500);
            $minDays     = \App\Models\Setting::get('delivery_days_min', 3);
            $maxDays     = \App\Models\Setting::get('delivery_days_max', 7);
        @endphp

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:3rem;">
            @foreach([
                ['fa-truck-fast','مدة التوصيل',"$minDays–$maxDays أيام عمل"],
                ['fa-coins','رسوم الشحن',"$shippingFee ر.س"],
                ['fa-gift','شحن مجاني',"للطلبات فوق $freeMin ر.س"],
                ['fa-box','تغليف متخصص','حماية كاملة للقطعة'],
            ] as [$icon,$title,$desc])
                <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.5rem;border:1px solid var(--color-border);text-align:center;">
                    <div style="width:52px;height:52px;background:rgba(182,138,74,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;font-size:1.2rem;color:var(--color-gold);">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div style="font-weight:700;color:var(--color-brown);margin-bottom:.3rem;">{{ $title }}</div>
                    <div style="font-size:.875rem;color:var(--color-gold);font-weight:600;">{{ $desc }}</div>
                </div>
            @endforeach
        </div>

        @foreach([
            ['التغليف','نستخدم مواد تغليف متخصصة تضمن وصول قطعتك سليمة. كل قطعة تُلفّ بالقطن الطبي والفقاعات الواقية قبل وضعها في صندوق مقوّى مصمم خصيصاً.'],
            ['مناطق التوصيل','نوصّل لجميع مناطق المملكة العربية السعودية. للدول الخليجية والعربية الأخرى، تواصل معنا للاستفسار عن التوفر والتكلفة.'],
            ['التتبع','بمجرد شحن طلبك ستصلك رسالة نصية برقم التتبع. يمكنك متابعة طلبك من صفحة "طلباتي" في حسابك.'],
        ] as [$title,$body])
            <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--color-border);margin-bottom:1rem;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--color-brown);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="width:6px;height:6px;background:var(--color-gold);border-radius:50%;flex-shrink:0;"></span>
                    {{ $title }}
                </h3>
                <p style="color:var(--color-text-muted);line-height:1.8;font-size:.9rem;">{{ $body }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection


{{-- ════ pages/returns.blade.php ════ --}}
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
            ['شروط الإرجاع',['القطعة في حالتها الأصلية','لم يتغير وضعها أو محتواها','مع الفاتورة وكل الملحقات','خلال 14 يوماً من الاستلام']],
            ['خطوات الإرجاع',['تواصل معنا عبر البريد أو واتساب','أخبرنا بسبب الإرجاع','سنرسل لك تعليمات التغليف والشحن','نستلم القطعة ونعيد المبلغ خلال 5 أيام عمل']],
        ] as [$title,$items])
            <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--color-border);margin-bottom:1rem;">
                <h3 style="font-weight:700;color:var(--color-brown);margin-bottom:1rem;">{{ $title }}</h3>
                @foreach($items as $i => $item)
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:{{ !$loop->last?'1px solid var(--color-border)':'none' }};">
                        <span style="width:22px;height:22px;background:var(--color-gold);color:var(--color-brown);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">{{ $i+1 }}</span>
                        <span style="font-size:.875rem;color:var(--color-text-muted);">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection


{{-- ════ pages/faq.blade.php ════ --}}
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
            ['هل تتوفر ضمانات على القطع؟','توفر بعض القطع ضماناً على الصيانة. تجد تفاصيل الضمان في صفحة كل منتج. كما نضمن أصالة كل قطعة وإلا نستردها ونعيد المبلغ كاملاً.'],
            ['ما هي طرق الدفع المتاحة؟','نقبل: الدفع عند الاستلام، والتحويل البنكي. نعمل على إضافة خيارات دفع إلكترونية إضافية قريباً.'],
            ['كيف أشارك في المزادات؟','تصفح صفحة المزادات وسجّل اهتمامك بالقطعة التي تريدها. سيتواصل معك فريقنا قبل موعد المزاد بجميع التفاصيل.'],
        ];
        @endphp

        <div style="display:flex;flex-direction:column;gap:.75rem;" id="faqList">
            @foreach($faqs as $i => $faq)
                <div style="background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);overflow:hidden;">
                    <button onclick="toggleFaq({{ $i }})"
                        style="width:100%;padding:1.25rem 1.5rem;background:none;border:none;font-family:var(--font-main);font-size:.925rem;font-weight:700;color:var(--color-brown);text-align:right;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                        {{ $faq[0] }}
                        <i class="fa-solid fa-chevron-down fa-sm" id="faq-icon-{{ $i }}" style="color:var(--color-gold);transition:transform .3s;flex-shrink:0;"></i>
                    </button>
                    <div id="faq-body-{{ $i }}" style="display:none;padding:0 1.5rem 1.25rem;color:var(--color-text-muted);font-size:.875rem;line-height:1.8;border-top:1px solid var(--color-border);">
                        {{ $faq[1] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:3rem;padding:2rem;background:var(--color-bg-2);border-radius:var(--radius-lg);border:1px solid var(--color-border);">
            <p style="color:var(--color-text-muted);margin-bottom:1rem;">لم تجد إجابة على سؤالك؟</p>
            <a href="{{ route('contact') }}" class="btn btn-primary">تواصل معنا</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFaq(i) {
    const body = document.getElementById('faq-body-'+i);
    const icon = document.getElementById('faq-icon-'+i);
    const open = body.style.display !== 'none';
    // أغلق الكل
    document.querySelectorAll('[id^="faq-body-"]').forEach(el => el.style.display='none');
    document.querySelectorAll('[id^="faq-icon-"]').forEach(el => el.style.transform='rotate(0)');
    if (!open) {
        body.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
@endpush
@endsection
