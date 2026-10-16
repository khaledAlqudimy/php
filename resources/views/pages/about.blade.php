{{-- ════ pages/about.blade.php ════ --}}
@extends('layouts.app')
@section('title','من نحن')
@section('meta_description', 'تعرّف على قطعة وقصة — معرض أنتيك سعودي يجمع القطع الأصيلة والنادرة ويحفظ قصصها.')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <span style="display:inline-block;background:rgba(182,138,74,.15);border:1px solid rgba(182,138,74,.3);color:var(--color-gold);font-size:.8rem;font-weight:700;padding:.35rem 1rem;border-radius:100px;margin-bottom:1rem;">قصتنا</span>
        <h1 style="font-size:clamp(1.8rem,4vw,2.75rem);font-weight:700;color:#fff;margin-bottom:1rem;">من نحن</h1>
        <p style="color:rgba(255,255,255,.65);max-width:520px;margin:0 auto;line-height:1.8;">نؤمن بأن كل قطعة أثرية تحمل قصة إنسانية تستحق أن تُروى</p>
    </div>
</div>

<div style="padding:5rem 0;">
    <div class="container">
        <div class="stack-md" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;margin-bottom:5rem;">
            <div>
                <span style="display:inline-block;color:var(--color-gold);font-size:.8rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:.75rem;">رؤيتنا</span>
                <h2 style="font-size:1.75rem;font-weight:700;color:var(--color-brown);margin-bottom:1.25rem;line-height:1.3;">معرض أنتيك فاخر يجمع بين الأصالة والرقي</h2>
                <p style="color:var(--color-text-muted);line-height:1.9;margin-bottom:1rem;">أُسّست «قطعة وقصة» بشغف حقيقي تجاه القطع التاريخية والتحف النادرة. نؤمن أن كل قطعة تحمل في طياتها قصة إنسانية فريدة تستحق أن تُحفظ وتُروى للأجيال القادمة.</p>
                <p style="color:var(--color-text-muted);line-height:1.9;">نسعى لأن نكون الوجهة الأولى لعشاق الاقتناء والجامعين في المملكة العربية السعودية والوطن العربي، نقدم لهم قطعاً موثّقة الأصالة بأسعار شفافة وخدمة استثنائية.</p>
            </div>
            <div style="background:var(--color-bg-2);border-radius:var(--radius-lg);padding:2.5rem;border:1px solid var(--color-border);">
                @foreach([['fa-gem','أكثر من 500 قطعة أصيلة','في مجموعتنا من مختلف الحقب والثقافات'],['fa-globe','شبكة عالمية واسعة','نستورد من أكثر من 30 دولة حول العالم'],['fa-shield-halved','ضمان الأصالة','كل قطعة موثّقة من خبرائنا المعتمدين'],['fa-star','أكثر من 5 سنوات خبرة','في عالم التحف والأنتيك']] as [$icon,$title,$desc])
                    <div style="display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.5rem;">
                        <div style="width:44px;height:44px;border-radius:var(--radius);background:rgba(182,138,74,.12);border:1px solid rgba(182,138,74,.2);display:flex;align-items:center;justify-content:center;color:var(--color-gold);flex-shrink:0;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--color-brown);margin-bottom:.2rem;">{{ $title }}</div>
                            <div style="font-size:.85rem;color:var(--color-text-muted);">{{ $desc }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- الفريق --}}
        <div style="text-align:center;margin-bottom:3rem;">
            <h2 style="font-size:1.5rem;font-weight:700;color:var(--color-brown);">فريق الخبراء</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
            @foreach([['الخبير الأول','متخصص في الأثاث الأوروبي والساعات','fa-user-tie'],['خبيرة التحف الشرقية','متخصصة في التحف والمنسوجات الإسلامية','fa-user'],['مدير الاستيراد','شبكة علاقات في أوروبا والشرق الأوسط','fa-user-tie']] as [$name,$role,$icon])
                <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--color-border);text-align:center;">
                    <div style="width:72px;height:72px;background:var(--color-bg-2);border:2px solid var(--color-border);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.75rem;color:var(--color-gold);">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div style="font-weight:700;color:var(--color-brown);margin-bottom:.25rem;">{{ $name }}</div>
                    <div style="font-size:.8rem;color:var(--color-text-muted);">{{ $role }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
