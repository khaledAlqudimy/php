{{-- ════ pages/services.blade.php ════ --}}
@extends('layouts.app')
@section('title','خدماتنا')
@section('meta_description', 'خدمات قطعة وقصة: بيع القطع الأثرية، المزادات، الباحث الشخصي، والتغليف والشحن الآمن.')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <span style="display:inline-block;background:rgba(182,138,74,.15);border:1px solid rgba(182,138,74,.3);color:var(--color-gold);font-size:.8rem;font-weight:700;padding:.35rem 1rem;border-radius:100px;margin-bottom:1rem;">ما نقدمه</span>
        <h1 style="font-size:2.25rem;font-weight:700;color:#fff;margin-bottom:.75rem;">خدماتنا</h1>
        <p style="color:rgba(255,255,255,.65);max-width:480px;margin:0 auto;">نقدم تجربة اقتناء متكاملة تتجاوز مجرد البيع والشراء</p>
    </div>
</div>

<div style="padding:5rem 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:2rem;">
            @foreach([
                ['fa-bag-shopping','المتجر الإلكتروني','تصفح مجموعتنا المتجددة من التحف والقطع الأثرية النادرة من مختلف الحقب والثقافات. كل قطعة موثّقة وتأتي مع ضمان الأصالة.',route('shop'),'تصفح المتجر'],
                ['fa-gavel','مزادات حصرية','شارك في مزاداتنا الدورية على أنفس القطع النادرة. سجّل اهتمامك وسيتواصل معك فريقنا بكل التفاصيل قبل موعد المزاد.',route('auction.index'),'اكتشف المزادات'],
                ['fa-magnifying-glass','الباحث الشخصي','أخبرنا عن القطعة التي تبحث عنها وسيعمل فريق خبرائنا على إيجادها من شبكة مصادرنا العالمية الحصرية.',route('personal-shopper.index'),'ابدأ البحث'],
                ['fa-shield-halved','تقييم وتوثيق','نقدم خدمة تقييم وتوثيق القطع الأثرية للأفراد والمجموعات الخاصة. احصل على شهادة أصالة معتمدة من خبرائنا.',route('contact'),'تواصل معنا'],
                ['fa-box','شحن متخصص','تغليف احترافي وشحن آمن لأي مكان في المملكة. نستخدم مواد تغليف متخصصة تضمن وصول قطعتك سليمة.',route('shipping'),'تفاصيل الشحن'],
                ['fa-handshake','الاستشارات','احجز جلسة استشارية مع أحد خبرائنا لتقييم مجموعتك أو للحصول على توجيه قبل الشراء.',route('contact'),'احجز استشارة'],
            ] as [$icon,$title,$desc,$link,$btn])
                <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:2rem;border:1px solid var(--color-border);transition:all .25s;display:flex;flex-direction:column;"
                     onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 32px rgba(58,36,24,.12)';this.style.borderColor='var(--color-gold)'"
                     onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--color-border)'">
                    <div style="width:56px;height:56px;background:rgba(182,138,74,.1);border:1.5px solid rgba(182,138,74,.2);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;color:var(--color-gold);font-size:1.35rem;margin-bottom:1.25rem;">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:700;color:var(--color-brown);margin-bottom:.75rem;">{{ $title }}</h3>
                    <p style="font-size:.875rem;color:var(--color-text-muted);line-height:1.8;flex:1;">{{ $desc }}</p>
                    <a href="{{ $link }}" style="display:inline-flex;align-items:center;gap:.4rem;margin-top:1.25rem;color:var(--color-gold);font-weight:700;font-size:.85rem;text-decoration:none;">
                        {{ $btn }} <i class="fa-solid fa-arrow-left fa-xs"></i>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div style="background:var(--color-brown);border-radius:var(--radius-lg);padding:3rem;margin-top:4rem;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;border:40px solid rgba(182,138,74,.08);border-radius:50%;"></div>
            <div style="position:relative;z-index:1;">
                <div style="color:var(--color-gold);font-size:.8rem;font-weight:700;letter-spacing:1px;margin-bottom:.75rem;">لديك سؤال؟</div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:.75rem;">فريقنا جاهز لمساعدتك</h2>
                <p style="color:rgba(255,255,255,.65);font-size:.9rem;margin-bottom:2rem;">تواصل معنا وسنرد عليك خلال 24 ساعة</p>
                <a href="{{ route('contact') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.875rem 2.5rem;background:var(--color-gold);color:var(--color-brown);border-radius:var(--radius);font-weight:700;font-size:1rem;text-decoration:none;">
                    <i class="fa-solid fa-envelope fa-sm"></i> تواصل معنا
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
