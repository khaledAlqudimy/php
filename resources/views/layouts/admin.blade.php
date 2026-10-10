<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'لوحة التحكم') — قطعة وقصة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --brown:      #3A2418;
            --brown-2:    #2A1A10;
            --gold:       #B68A4A;
            --gold-2:     #C79A52;
            --sidebar-w:  260px;
            --header-h:   64px;
            --font:       'IBM Plex Sans Arabic', sans-serif;
            --bg:         #F4F1EC;
            --white:      #FFFFFF;
            --border:     #E2D9CE;
            --text:       #2C1810;
            --muted:      #8B7355;
            --danger:     #DC2626;
            --success:    #16A34A;
            --radius:     8px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* ─── Sidebar ─── */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--brown-2);
            position: fixed; top: 0; right: 0;
            height: 100vh;
            display: flex; flex-direction: column;
            overflow-y: auto;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            text-align: center;
        }

        .sidebar-brand .logo {
            font-size: 1.3rem; font-weight: 700;
            color: var(--gold);
        }

        .sidebar-brand .logo span { color: #fff; }
        .sidebar-brand small { color: rgba(255,255,255,.4); font-size: .75rem; }

        .sidebar-nav { flex: 1; padding: 1rem 0; }

        .nav-section-title {
            padding: .5rem 1.25rem;
            font-size: .7rem;
            font-weight: 700;
            color: rgba(255,255,255,.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: .5rem;
        }

        .nav-item {
            display: flex; align-items: center; gap: .75rem;
            padding: .7rem 1.25rem;
            color: rgba(255,255,255,.65);
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            border-right: 3px solid transparent;
            text-decoration: none;
        }

        .nav-item:hover { color: #fff; background: rgba(255,255,255,.05); }

        .nav-item.active {
            color: var(--gold);
            border-right-color: var(--gold);
            background: rgba(182,138,74,.08);
        }

        .nav-item i { width: 18px; text-align: center; font-size: .95rem; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .admin-info {
            display: flex; align-items: center; gap: .75rem;
            margin-bottom: .75rem;
        }

        .admin-avatar {
            width: 36px; height: 36px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: var(--brown); font-size: .875rem;
        }

        .admin-name { font-size: .875rem; color: #fff; font-weight: 600; }
        .admin-role { font-size: .75rem; color: rgba(255,255,255,.4); }

        .logout-btn {
            display: flex; align-items: center; gap: .5rem;
            width: 100%; padding: .6rem 1rem;
            background: rgba(220,38,38,.15);
            color: #FCA5A5;
            border: none; border-radius: var(--radius);
            font-family: var(--font); font-size: .85rem; cursor: pointer;
            transition: all .2s;
        }

        .logout-btn:hover { background: rgba(220,38,38,.25); }

        /* ─── Main Content ─── */
        .admin-main {
            margin-right: var(--sidebar-w);
            flex: 1;
            display: flex; flex-direction: column;
        }

        .admin-topbar {
            height: var(--header-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        .page-title { font-size: 1.1rem; font-weight: 700; color: var(--brown); }

        .topbar-right { display: flex; align-items: center; gap: 1rem; }

        .topbar-link {
            color: var(--muted); font-size: .85rem;
            display: flex; align-items: center; gap: .35rem;
            text-decoration: none;
        }

        .topbar-link:hover { color: var(--brown); }

        .admin-content { flex: 1; padding: 1.5rem; }

        /* ─── Flash ─── */
        .admin-flash {
            padding: .875rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: .75rem;
            font-weight: 600; font-size: .9rem;
        }

        .admin-flash.success { background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }
        .admin-flash.error   { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }

        /* ─── زر القائمة (جوال) ─── */
        .admin-menu-btn {
            display: none; width: 40px; height: 40px; border-radius: var(--radius);
            background: var(--bg); border: 1px solid var(--border); cursor: pointer;
            position: relative; flex-shrink: 0;
        }
        .admin-menu-btn span, .admin-menu-btn span::before, .admin-menu-btn span::after {
            position: absolute; right: 10px; width: 18px; height: 2px; border-radius: 2px; background: var(--brown); content: '';
        }
        .admin-menu-btn span { top: 19px; }
        .admin-menu-btn span::before { right: 0; top: -6px; }
        .admin-menu-btn span::after  { right: 0; top: 6px; }
        .admin-backdrop { display: none; }

        /* ─── تجاوب: تابلت وجوال ─── */
        .admin-main { min-width: 0; }
        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(100%); transition: transform .25s ease; z-index: 60; box-shadow: -6px 0 24px rgba(0,0,0,.25); }
            body.sidebar-open .admin-sidebar { transform: none; }
            body.sidebar-open { overflow: hidden; }
            .admin-backdrop { position: fixed; inset: 0; background: rgba(20,12,8,.5); z-index: 55; }
            body.sidebar-open .admin-backdrop { display: block; }
            .admin-main { margin-right: 0; width: 100%; }
            .admin-menu-btn { display: inline-block; }
            .admin-topbar { padding: 0 1rem; gap: .75rem; }
            .admin-topbar .page-title { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 1rem; }
            .admin-content { padding: 1rem; }

            /* الجداول تتمرر أفقياً داخل إطارها بدل أن تخرج من الشاشة */
            .admin-content div:has(> table) { overflow-x: auto !important; -webkit-overflow-scrolling: touch; }
            .admin-content table { min-width: 640px; }
        }
        @media (max-width: 768px) {
            /* الأعمدة المتجاورة تنزل تحت بعض */
            .admin-content [style*="grid-template-columns"],
            .admin-content .settings-grid,
            .admin-content .set-row-2 { grid-template-columns: 1fr !important; }
            .topbar-link .link-text { display: none; }
            .admin-content [style*="grid-template-columns"] > * { min-width: 0; }
            /* أشرطة الفلاتر والبحث تنكسر لأكثر من سطر */
            .admin-content form[style*="display:flex"],
            .admin-content form > [style*="display:flex"],
            .admin-content .filter-bar,
            .admin-content .toolbar, .admin-content .search-bar { flex-wrap: wrap; }
            .admin-content .search-bar > * { flex: 1 1 140px; }
            .admin-content form[style*="display:flex"] > *,
            .admin-content form > [style*="display:flex"] > * { flex: 1 1 140px; }
        }

        @stack('admin-styles')
    </style>
    @stack('styles')
</head>
<body>

{{-- ─── Sidebar ─── --}}
<div class="admin-backdrop" id="adminBackdrop"></div>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="logo">قطعة <span>وقصة</span></div>
        <small>لوحة التحكم</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">الرئيسية</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> لوحة التحكم
        </a>

        <div class="nav-section-title">المنتجات</div>
        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i> المنتجات
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> التصنيفات
        </a>

        <div class="nav-section-title">المبيعات</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fa-solid fa-receipt"></i> الطلبات
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> العملاء
        </a>
        @if(auth()->user()?->isManager())
        <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket"></i> الكوبونات
        </a>
        @endif

        <div class="nav-section-title">الخدمات</div>
        <a href="{{ route('admin.auction-interest.index') }}" class="nav-item {{ request()->routeIs('admin.auction-interest*') ? 'active' : '' }}">
            <i class="fa-solid fa-gavel"></i> اهتمامات المزادات
        </a>
        <a href="{{ route('admin.personal-shopper.index') }}" class="nav-item {{ request()->routeIs('admin.personal-shopper*') ? 'active' : '' }}">
            <i class="fa-solid fa-magnifying-glass"></i> الباحث الشخصي
        </a>
        <a href="{{ route('admin.messages.index') }}" class="nav-item {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
            <i class="fa-solid fa-envelope"></i> رسائل التواصل
        </a>

        @if(auth()->user()?->isManager())
        <div class="nav-section-title">الإدارة</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-bar"></i> التقارير
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i> الإعدادات
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="admin-info">
            <div class="admin-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
            <div>
                <div class="admin-name">{{ auth()->user()->name }}</div>
                <div class="admin-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        @endauth
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</aside>

{{-- ─── Main ─── --}}
<div class="admin-main">
    <div class="admin-topbar">
        <button type="button" class="admin-menu-btn" id="adminMenuBtn" aria-label="القائمة" aria-controls="adminSidebar" aria-expanded="false"><span aria-hidden="true"></span></button>
        <div class="page-title">@yield('page-title', 'لوحة التحكم')</div>
        <div class="topbar-right">
            <a href="{{ route('home') }}" class="topbar-link" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="link-text">عرض الموقع</span>
            </a>
        </div>
    </div>

    <div class="admin-content">
        @if(session('success'))
            <div class="admin-flash success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="admin-flash error">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    // ─── قائمة لوحة الإدارة على الجوال ───
    (function () {
        const btn = document.getElementById('adminMenuBtn');
        const backdrop = document.getElementById('adminBackdrop');
        const set = (open) => { document.body.classList.toggle('sidebar-open', open); btn.setAttribute('aria-expanded', open); };
        btn.addEventListener('click', () => set(!document.body.classList.contains('sidebar-open')));
        backdrop.addEventListener('click', () => set(false));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') set(false); });
        window.addEventListener('resize', () => { if (innerWidth > 1024) set(false); });
    })();
</script>
@stack('scripts')
</body>
</html>
