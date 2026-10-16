{{-- ════ pages/shipping.blade.php ════ --}}
@extends('layouts.app')
@section('title','الشحن والتوصيل')
@section('meta_description', 'سياسة الشحن والتوصيل في قطعة وقصة: المدة، الرسوم، والشحن المجاني والتغليف الآمن للقطع الأثرية.')
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
                ['fa-truck-fast','مدة التوصيل',"{$minDays}–{$maxDays} أيام عمل"],
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
