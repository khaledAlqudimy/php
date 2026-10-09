@extends('layouts.app')
@section('title','المجموعات')
@section('meta_description', 'مجموعات قطعة وقصة: تصفّح القطع الأثرية حسب الفئة والحقبة.')

@section('content')
<div style="background:var(--color-brown);padding:4rem 0 3.5rem;text-align:center;">
    <div class="container">
        <h1 style="font-size:2.25rem;font-weight:700;color:var(--color-gold);margin-bottom:.75rem;">المجموعات</h1>
        <p style="color:rgba(255,255,255,.65);max-width:480px;margin:0 auto;">تصفح قطعنا مصنّفةً حسب الحقبة والأصل والنوع</p>
    </div>
</div>

<div style="padding:4rem 0 5rem;">
    <div class="container">

        {{-- مجموعات حسب الحقبة --}}
        <div style="margin-bottom:4rem;">
            <div style="text-align:center;margin-bottom:2rem;">
                <span class="section-label">تصنيف زمني</span>
                <h2 class="section-title">حسب الحقبة التاريخية</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.25rem;">
                @foreach([
                    ['القرن التاسع عشر','1800–1899','fa-industry','#3A2418'],
                    ['الحقبة الفيكتورية','1837–1901','fa-crown','#2A1A10'],
                    ['عصر النهضة','1400–1600','fa-paintbrush','#1A0F08'],
                    ['الحقبة العثمانية','1299–1922','fa-mosque','#3A2418'],
                    ['مطلع القرن العشرين','1900–1950','fa-car','#2A1A10'],
                    ['ما قبل 1800','قبل القرن التاسع عشر','fa-scroll','#1A0F08'],
                ] as [$era,$range,$icon,$bg])
                    <a href="{{ route('shop', ['era' => $era]) }}"
                       style="background:{{ $bg }};border-radius:var(--radius-lg);padding:1.5rem;text-align:center;text-decoration:none;transition:all .25s;display:block;border:1px solid rgba(182,138,74,.2);"
                       onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--color-gold)'"
                       onmouseout="this.style.transform='';this.style.borderColor='rgba(182,138,74,.2)'">
                        <div style="font-size:2rem;color:var(--color-gold);margin-bottom:.75rem;opacity:.8;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div style="font-weight:700;color:#fff;margin-bottom:.3rem;font-size:.9rem;">{{ $era }}</div>
                        <div style="font-size:.75rem;color:rgba(255,255,255,.5);">{{ $range }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- مجموعات حسب النوع --}}
        <div style="margin-bottom:4rem;">
            <div style="text-align:center;margin-bottom:2rem;">
                <span class="section-label">تصنيف نوعي</span>
                <h2 class="section-title">حسب نوع القطعة</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.25rem;">
                @foreach([
                    ['الأثاث الكلاسيكي','🪑','classic-furniture','كراسي، طاولات، خزائن من مختلف الحقب'],
                    ['الساعات والمنبّهات','🕰️','vintage-clocks','ساعات حائط وطاولة ومنبّهات نادرة'],
                    ['التحف النحاسية','🥈','silver-brass','آنية وتحف من الفضة والنحاس الأصيل'],
                    ['اللوحات والفنون','🖼️','paintings-art','لوحات زيتية وأعمال فنية تراثية'],
                    ['الإضاءة والثريات','💡','lighting-chandeliers','نجف وثريات كلاسيكية فاخرة'],
                    ['مجموعات نادرة','🏺','antiques-collectibles','قطع نادرة للمقتنين والهواة'],
                ] as [$name,$emoji,$slug,$desc])
                    <a href="{{ route('shop', ['category' => $slug]) }}"
                       style="background:var(--color-white);border-radius:var(--radius-lg);padding:1.5rem;border:1px solid var(--color-border);text-decoration:none;display:flex;align-items:center;gap:1rem;transition:all .25s;"
                       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)';this.style.borderColor='var(--color-gold)'"
                       onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--color-border)'">
                        <div style="font-size:2.25rem;flex-shrink:0;">{{ $emoji }}</div>
                        <div>
                            <div style="font-weight:700;color:var(--color-brown);margin-bottom:.2rem;font-size:.9rem;">{{ $name }}</div>
                            <div style="font-size:.78rem;color:var(--color-text-muted);line-height:1.5;">{{ $desc }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- مجموعات خاصة --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
            <a href="{{ route('shop', ['rare' => 1]) }}"
               style="background:linear-gradient(135deg,#4C1D95,#7C3AED);border-radius:var(--radius-lg);padding:2.5rem;text-decoration:none;position:relative;overflow:hidden;transition:transform .25s;"
               onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                <div style="position:absolute;bottom:-40px;left:-40px;width:160px;height:160px;border:30px solid rgba(255,255,255,.06);border-radius:50%;"></div>
                <div style="position:relative;z-index:1;">
                    <i class="fa-solid fa-gem fa-2x" style="color:rgba(255,255,255,.6);margin-bottom:1rem;"></i>
                    <h3 style="font-size:1.25rem;font-weight:700;color:#fff;margin-bottom:.5rem;">القطع النادرة</h3>
                    <p style="color:rgba(255,255,255,.65);font-size:.875rem;">مجموعة حصرية من أندر القطع التي تضمها مجموعتنا</p>
                    <span style="display:inline-flex;align-items:center;gap:.4rem;margin-top:1rem;color:#fff;font-weight:700;font-size:.85rem;">
                        تصفح الآن <i class="fa-solid fa-arrow-left fa-xs"></i>
                    </span>
                </div>
            </a>
            <a href="{{ route('shop', ['featured' => 1]) }}"
               style="background:linear-gradient(135deg,var(--color-brown),#2A1A10);border-radius:var(--radius-lg);padding:2.5rem;text-decoration:none;position:relative;overflow:hidden;border:2px solid rgba(182,138,74,.3);transition:transform .25s;"
               onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                <div style="position:absolute;bottom:-40px;left:-40px;width:160px;height:160px;border:30px solid rgba(182,138,74,.06);border-radius:50%;"></div>
                <div style="position:relative;z-index:1;">
                    <i class="fa-solid fa-star fa-2x" style="color:var(--color-gold);opacity:.7;margin-bottom:1rem;"></i>
                    <h3 style="font-size:1.25rem;font-weight:700;color:#fff;margin-bottom:.5rem;">مختارات المعرض</h3>
                    <p style="color:rgba(255,255,255,.65);font-size:.875rem;">قطع انتقاها خبراؤنا بعناية فائقة لتكون في الصدارة</p>
                    <span style="display:inline-flex;align-items:center;gap:.4rem;margin-top:1rem;color:var(--color-gold);font-weight:700;font-size:.85rem;">
                        تصفح الآن <i class="fa-solid fa-arrow-left fa-xs"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
