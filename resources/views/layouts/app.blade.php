<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'قطعة وقصة') — معرض الأنتيك الفاخر</title>

    {{-- ═══ SEO ═══ --}}
    @php
        // yieldContent يُرجع نصاً مُهرّباً مسبقاً → نفك التهريب حتى لا يتكرر مع {{ }}
        $decode          = fn ($v) => trim(html_entity_decode($v, ENT_QUOTES, 'UTF-8'));
        $metaTitle       = $decode($__env->yieldContent('title', 'قطعة وقصة')) . ' — معرض الأنتيك الفاخر';
        $metaDescription = $decode($__env->yieldContent('meta_description'))
            ?: 'قطعة وقصة — معرض أنتيك فاخر في السعودية: قطع أثرية أصيلة ونادرة من الأثاث الكلاسيكي والساعات والتحف، لكل قطعة قصة موثّقة.';
        $metaImage       = $decode($__env->yieldContent('og_image'));
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @hasSection('noindex')
        <meta name="robots" content="noindex, nofollow">
    @endif
    <meta name="theme-color" content="#3A2418">

    <meta property="og:site_name" content="قطعة وقصة">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
    @endif
    <meta name="twitter:card" content="{{ $metaImage ? 'summary_large_image' : 'summary' }}">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @stack('head')

    {{-- خطوط Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- أيقونات --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ═══ متغيرات الألوان — مطابقة للأصل ═══ */
        :root {
            --color-brown:      #3A2418;
            --color-brown-light:#5C3D2E;
            --color-gold:       #B68A4A;
            --color-gold-2:     #C79A52;
            --color-gold-light: #D4A96A;
            --color-bg:         #F8F4EE;
            --color-bg-2:       #F0EAE0;
            --color-text:       #2C1810;
            --color-text-muted: #6B5744;
            --color-border:     #D4C4B0;
            --color-white:      #FFFFFF;
            --font-main:        'IBM Plex Sans Arabic', 'Cairo', sans-serif;
            --shadow-sm:        0 1px 3px rgba(58,36,24,.08);
            --shadow-md:        0 4px 12px rgba(58,36,24,.12);
            --shadow-lg:        0 8px 24px rgba(58,36,24,.16);
            --radius:           8px;
            --radius-lg:        16px;
            --transition:       all .25s ease;
        }

        /* ═══ إعادة الضبط ═══ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family:      var(--font-main);
            background-color: var(--color-bg);
            color:            var(--color-text);
            line-height:      1.7;
            min-height:       100vh;
            display:          flex;
            flex-direction:   column;
        }

        a { color: inherit; text-decoration: none; transition: var(--transition); }
        img { max-width: 100%; display: block; }

        /* ═══ Flash Messages ═══ */
        .flash-container {
            position: fixed;
            top: 80px; left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            pointer-events: none;
        }

        .flash {
            padding: .75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            pointer-events: all;
            animation: slideDown .3s ease;
        }

        .flash-success { background: #166534; color: #fff; }
        .flash-error   { background: #991B1B; color: #fff; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══ Main Content ═══ */
        .main-content { flex: 1; }

        /* ═══ Footer ═══ */
        .site-footer {
            background: var(--color-brown);
            color: rgba(255,255,255,.75);
            padding: 3rem 1.5rem 1.5rem;
            margin-top: auto;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-brand .site-logo { font-size: 1.25rem; margin-bottom: .75rem; }
        .footer-brand p { font-size: .875rem; opacity: .8; line-height: 1.6; }

        .footer-col h4 {
            color: var(--color-gold);
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .footer-col a {
            display: block;
            color: rgba(255,255,255,.7);
            font-size: .875rem;
            margin-bottom: .5rem;
        }

        .footer-col a:hover { color: var(--color-gold); }

        .footer-bottom {
            max-width: 1280px;
            margin: 2rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,.1);
            text-align: center;
            font-size: .8rem;
            opacity: .6;
        }

        /* ═══ Utilities ═══ */
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.5rem;
            border-radius: var(--radius);
            font-family: var(--font-main);
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--color-gold);
            color: var(--color-brown);
        }

        .btn-primary:hover { background: var(--color-gold-2); transform: translateY(-1px); }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--color-gold);
            color: var(--color-gold);
        }

        .btn-outline:hover { background: var(--color-gold); color: var(--color-brown); }

        /* تخطيطات الأعمدة تنزل تحت بعض على الجوال */
        @media (max-width: 768px) {
            .stack-md { grid-template-columns: 1fr !important; gap: 1.5rem !important; }
        }

        /* ═══ زر واتساب عائم ═══ */
        .wa-float {
            position: fixed;
            bottom: 1.25rem; left: 1.25rem;
            width: 56px; height: 56px;
            border-radius: 50%;
            background: #25D366;
            color: #fff;
            font-size: 1.75rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,.25);
            z-index: 90;
        }
        .wa-float:hover { transform: scale(1.08); color: #fff; }
        @media (max-width: 768px) {
            .wa-float { width: 46px; height: 46px; font-size: 1.45rem; bottom: .9rem; left: .9rem; opacity: .92; }
        }

    </style>

    @stack('styles')
</head>
<body>

{{-- ═══ Header + القائمة الرئيسية ═══ --}}
@include('layouts.partials.header')

{{-- ═══ Flash Messages ═══ --}}
<div class="flash-container" id="flashContainer">
    @if(session('success'))
        <div class="flash flash-success">
            <i class="fa-solid fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif
</div>

{{-- ═══ Main Content ═══ --}}
<main class="main-content">
    @yield('content')
</main>

{{-- ═══ زر واتساب عائم ═══ --}}
@if(! request()->routeIs('cart.*', 'checkout', 'account.*', 'login', 'register', 'password.*')
    && ($floatingWa = \App\Models\Setting::whatsappLink('السلام عليكم، لدي استفسار عن قطعة في قطعة وقصة')))
    <a href="{{ $floatingWa }}" class="wa-float" target="_blank" rel="noopener" aria-label="تواصل عبر واتساب">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
@endif

{{-- ═══ Footer ═══ --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="site-logo">قطعة <span>وقصة</span></div>
            <p>كل قطعة تحمل روحاً وتروي قصة.<br>معرض أنتيك فاخر للقطع الأصيلة والنادرة.</p>
        </div>
        <div class="footer-col">
            <h4>تصفح</h4>
            <a href="{{ route('shop') }}">المتجر</a>
            <a href="{{ route('auction.index') }}">المزادات</a>
            <a href="{{ route('personal-shopper.index') }}">الباحث الشخصي</a>
            <a href="{{ route('blog.index') }}">المدونة</a>
        </div>
        <div class="footer-col">
            <h4>خدمات</h4>
            <a href="{{ route('about') }}">من نحن</a>
            <a href="{{ route('shipping') }}">الشحن والتوصيل</a>
            <a href="{{ route('returns') }}">الإرجاع والاسترداد</a>
            <a href="{{ route('faq') }}">الأسئلة الشائعة</a>
        </div>
        <div class="footer-col">
            <h4>تواصل معنا</h4>
            <a href="{{ route('contact') }}">اتصل بنا</a>
            @if($waLink = \App\Models\Setting::whatsappLink())
                <a href="{{ $waLink }}" target="_blank" rel="noopener">واتساب</a>
            @endif
            @if($sitePhone = \App\Models\Setting::contact('site_phone'))
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $sitePhone) }}" dir="ltr" style="text-align:right;">{{ $sitePhone }}</a>
            @endif
        </div>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} قطعة وقصة — جميع الحقوق محفوظة
        <span style="margin:0 .5rem;opacity:.5;">|</span>
        <a href="{{ route('privacy') }}">سياسة الخصوصية</a>
        <span style="margin:0 .5rem;opacity:.5;">|</span>
        <a href="{{ route('terms') }}">الشروط والأحكام</a>
    </div>
</footer>

<script>
    // ─── إخفاء Flash Messages بعد 4 ثوان ───
    setTimeout(() => {
        document.querySelectorAll('.flash').forEach(el => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
