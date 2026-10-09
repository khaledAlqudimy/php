{{--
    الهيدر والقائمة الرئيسية
    - كمبيوتر: قائمة أفقية + قائمة منسدلة للتصنيفات + قائمة الحساب
    - تابلت/جوال (≤1024px): درج جانبي فيه كل الروابط والبحث والحساب
    المتغيرات: $navCategories (من View Composer في AppServiceProvider)
--}}
@php
    $user        = auth()->user();
    $cartCount   = $user ? $user->cartItems()->count() : 0;
    $showAuction = (bool) \App\Models\Setting::get('auction_enabled', 1);
    $showShopper = (bool) \App\Models\Setting::get('personal_shopper_enabled', 1);

    $isShop = request()->routeIs('shop', 'product.show', 'collections');

    // روابط القائمة الرئيسية (تُستخدم في الكمبيوتر والجوال)
    $mainLinks = array_values(array_filter([
        ['route' => 'home',                   'label' => 'الرئيسية',      'icon' => 'fa-house',            'active' => request()->routeIs('home')],
        ['route' => 'shop',                   'label' => 'المتجر',        'icon' => 'fa-store',            'active' => $isShop, 'dropdown' => true],
        $showAuction ? ['route' => 'auction.index', 'label' => 'المزادات', 'icon' => 'fa-gavel', 'active' => request()->routeIs('auction*')] : null,
        $showShopper ? ['route' => 'personal-shopper.index', 'label' => 'الباحث الشخصي', 'icon' => 'fa-magnifying-glass', 'active' => request()->routeIs('personal-shopper*')] : null,
        ['route' => 'blog.index',             'label' => 'المدونة',       'icon' => 'fa-feather',          'active' => request()->routeIs('blog*')],
        ['route' => 'contact',                'label' => 'تواصل معنا',    'icon' => 'fa-envelope',         'active' => request()->routeIs('contact')],
    ]));

    // روابط ثانوية (الدرج الجانبي فقط)
    $moreLinks = [
        ['route' => 'about',    'label' => 'من نحن'],
        ['route' => 'services', 'label' => 'خدماتنا'],
        ['route' => 'shipping', 'label' => 'الشحن والتوصيل'],
        ['route' => 'returns',  'label' => 'الإرجاع والاسترداد'],
        ['route' => 'faq',      'label' => 'الأسئلة الشائعة'],
    ];

    $accountLinks = [
        ['route' => 'account.profile',   'label' => 'حسابي',    'icon' => 'fa-circle-user'],
        ['route' => 'account.orders',    'label' => 'طلباتي',   'icon' => 'fa-receipt'],
        ['route' => 'account.wishlist',  'label' => 'المفضلة',  'icon' => 'fa-heart'],
        ['route' => 'account.addresses', 'label' => 'عناويني',  'icon' => 'fa-location-dot'],
    ];
@endphp

<style>
    /* ═══ Header ═══ */
    .site-header {
        background: var(--color-brown);
        position: sticky; top: 0; z-index: 100;
        box-shadow: var(--shadow-md);
        border-bottom: 1px solid rgba(182,138,74,.22);
    }
    .header-inner {
        max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;
        height: 72px; display: flex; align-items: center; gap: 1.25rem;
    }
    .site-logo { font-size: 1.5rem; font-weight: 700; color: var(--color-gold); letter-spacing: -.5px; white-space: nowrap; flex-shrink: 0; }
    .site-logo span { color: var(--color-white); }

    /* ─── القائمة الأفقية (كمبيوتر) ─── */
    .header-nav { display: flex; align-items: center; gap: .1rem; margin-inline: auto; }
    .nav-item { position: relative; }
    .nav-link {
        display: inline-flex; align-items: center; gap: .35rem;
        color: rgba(255,255,255,.85); font-size: .92rem; font-weight: 500;
        padding: .55rem .8rem; border-radius: var(--radius); white-space: nowrap;
        background: none; border: none; font-family: inherit; cursor: pointer;
        position: relative;
    }
    .nav-link:hover, .nav-link:focus-visible, .nav-item:focus-within > .nav-link { color: var(--color-gold); }
    .nav-link.active { color: var(--color-gold); }
    .nav-link.active::after {
        content: ''; position: absolute; bottom: -2px; right: .8rem; left: .8rem;
        height: 2px; border-radius: 2px; background: var(--color-gold);
    }
    .nav-caret { font-size: .65rem; opacity: .7; transition: transform .2s; }
    .nav-item.has-dropdown:hover .nav-caret,
    .nav-item.has-dropdown:focus-within .nav-caret { transform: rotate(180deg); }

    .nav-dropdown {
        position: absolute; top: calc(100% + 10px); right: 0; min-width: 240px;
        background: var(--color-white); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg); border: 1px solid var(--color-border);
        padding: .5rem; opacity: 0; visibility: hidden; transform: translateY(6px);
        transition: opacity .18s, transform .18s, visibility .18s;
    }
    .nav-dropdown::before { content: ''; position: absolute; top: -12px; right: 0; left: 0; height: 12px; } /* جسر للماوس */
    .nav-item.has-dropdown:hover .nav-dropdown,
    .nav-item.has-dropdown:focus-within .nav-dropdown { opacity: 1; visibility: visible; transform: none; }
    .nav-dropdown a {
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
        padding: .6rem .8rem; border-radius: var(--radius);
        color: var(--color-text); font-size: .88rem;
    }
    .nav-dropdown a:hover { background: var(--color-bg); color: var(--color-gold); }
    .nav-dropdown .dd-sep { height: 1px; background: var(--color-border); margin: .35rem .5rem; }
    .nav-dropdown .dd-strong { font-weight: 700; color: var(--color-brown); }

    /* ─── أزرار الهيدر ─── */
    .header-actions { display: flex; align-items: center; gap: .35rem; flex-shrink: 0; }
    .header-icon {
        position: relative; display: inline-flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; border-radius: 50%;
        color: rgba(255,255,255,.85); font-size: 1.05rem;
        background: none; border: none; cursor: pointer; transition: var(--transition);
    }
    .header-icon:hover, .header-icon[aria-expanded="true"] { color: var(--color-gold); background: rgba(255,255,255,.08); }
    .cart-count {
        position: absolute; top: 2px; right: 0;
        background: var(--color-gold); color: var(--color-brown);
        font-size: .65rem; font-weight: 700; min-width: 18px; height: 18px; padding: 0 4px;
        border-radius: 100px; display: flex; align-items: center; justify-content: center;
    }
    .header-auth { display: flex; align-items: center; gap: .5rem; margin-inline-start: .35rem; }
    .header-auth .btn { font-size: .85rem; padding: .5rem 1rem; }
    .header-login { color: rgba(255,255,255,.85); font-size: .9rem; font-weight: 600; padding: .5rem .75rem; }
    .header-login:hover { color: var(--color-gold); }

    /* قائمة الحساب */
    .account-menu { position: relative; }
    .account-btn {
        display: inline-flex; align-items: center; gap: .5rem; padding: .3rem .4rem .3rem .75rem;
        border-radius: 100px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        color: #fff; font-family: inherit; font-size: .85rem; cursor: pointer; margin-inline-start: .25rem;
    }
    .account-btn:hover, .account-btn[aria-expanded="true"] { border-color: var(--color-gold); }
    .account-avatar {
        width: 30px; height: 30px; border-radius: 50%; background: var(--color-gold); color: var(--color-brown);
        display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem;
    }
    .account-name { max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .account-dropdown { left: 0; right: auto; min-width: 220px; }
    .account-menu.open .account-dropdown { opacity: 1; visibility: visible; transform: none; }
    .account-dropdown .dd-head { padding: .6rem .8rem .5rem; font-size: .78rem; color: var(--color-text-muted); }
    .account-dropdown .dd-head strong { display: block; color: var(--color-brown); font-size: .9rem; }
    .account-dropdown a i, .account-dropdown button i { width: 18px; color: var(--color-gold); }
    .account-dropdown a { justify-content: flex-start; }
    .dd-logout {
        width: 100%; display: flex; align-items: center; gap: .5rem; padding: .6rem .8rem; border-radius: var(--radius);
        background: none; border: none; font-family: inherit; font-size: .88rem; color: #B91C1C; cursor: pointer; text-align: right;
    }
    .dd-logout:hover { background: #FEF2F2; }
    .dd-logout i { color: #B91C1C !important; }

    /* ─── شريط البحث ─── */
    .header-search { display: none; border-top: 1px solid rgba(255,255,255,.08); background: var(--color-brown); }
    .header-search.open { display: block; }
    .search-form { max-width: 720px; margin: 0 auto; padding: .85rem 1.5rem; display: flex; gap: .5rem; }
    .search-form input {
        flex: 1; min-width: 0; padding: .7rem 1rem; border-radius: var(--radius); border: 1.5px solid rgba(255,255,255,.15);
        background: rgba(255,255,255,.06); color: #fff; font-family: inherit; font-size: .95rem;
    }
    .search-form input::placeholder { color: rgba(255,255,255,.5); }
    .search-form input:focus { outline: none; border-color: var(--color-gold); }
    .search-form button { padding: .7rem 1.1rem; border-radius: var(--radius); border: none; background: var(--color-gold); color: var(--color-brown); font-family: inherit; font-weight: 700; cursor: pointer; }

    /* ─── زر القائمة (مرسوم بـ CSS — لا يعتمد على مكتبة الأيقونات) ─── */
    .menu-toggle {
        display: none; width: 42px; height: 42px; border-radius: var(--radius);
        background: none; border: none; cursor: pointer; position: relative; flex-shrink: 0;
    }
    .menu-toggle span, .menu-toggle span::before, .menu-toggle span::after {
        position: absolute; right: 10px; width: 22px; height: 2px; border-radius: 2px; background: #fff; content: '';
        transition: transform .2s, top .2s, opacity .2s;
    }
    .menu-toggle span { top: 20px; }
    .menu-toggle span::before { right: 0; top: -7px; }
    .menu-toggle span::after  { right: 0; top: 7px; }
    .menu-toggle:hover span, .menu-toggle:hover span::before, .menu-toggle:hover span::after { background: var(--color-gold); }

    /* ─── الدرج الجانبي (جوال/تابلت) ─── */
    .drawer-backdrop {
        position: fixed; inset: 0; background: rgba(20,12,8,.55); z-index: 200;
        opacity: 0; visibility: hidden; transition: opacity .25s, visibility .25s;
    }
    .mobile-drawer {
        position: fixed; top: 0; bottom: 0; right: 0; z-index: 201;
        width: min(86vw, 360px); background: var(--color-brown); color: #fff;
        display: flex; flex-direction: column;
        transform: translateX(100%); transition: transform .28s ease; visibility: hidden;
        box-shadow: -8px 0 30px rgba(0,0,0,.3);
    }
    html.drawer-open { overflow: hidden; }
    html.drawer-open .drawer-backdrop { opacity: 1; visibility: visible; }
    html.drawer-open .mobile-drawer { transform: none; visibility: visible; }

    .drawer-head { display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem; height: 72px; border-bottom: 1px solid rgba(255,255,255,.08); flex-shrink: 0; }
    .drawer-close {
        width: 40px; height: 40px; border-radius: 50%; border: none; background: rgba(255,255,255,.08);
        color: #fff; font-size: 1.4rem; line-height: 1; cursor: pointer; font-family: inherit;
    }
    .drawer-body { flex: 1; overflow-y: auto; padding: 1rem 1.25rem 1.5rem; overscroll-behavior: contain; }
    .drawer-search { display: flex; gap: .4rem; margin-bottom: 1rem; }
    .drawer-search input {
        flex: 1; min-width: 0; padding: .7rem .9rem; border-radius: var(--radius); border: 1.5px solid rgba(255,255,255,.15);
        background: rgba(255,255,255,.06); color: #fff; font-family: inherit; font-size: .95rem;
    }
    .drawer-search input::placeholder { color: rgba(255,255,255,.5); }
    .drawer-search input:focus { outline: none; border-color: var(--color-gold); }
    .drawer-search button { width: 46px; border: none; border-radius: var(--radius); background: var(--color-gold); color: var(--color-brown); cursor: pointer; }

    .drawer-nav a, .drawer-nav summary {
        display: flex; align-items: center; gap: .75rem; padding: .85rem .5rem;
        color: rgba(255,255,255,.9); font-size: 1rem; border-bottom: 1px solid rgba(255,255,255,.06);
        cursor: pointer; list-style: none;
    }
    .drawer-nav summary::-webkit-details-marker { display: none; }
    .drawer-nav a i, .drawer-nav summary i { width: 20px; text-align: center; color: var(--color-gold); opacity: .85; }
    .drawer-nav a.active, .drawer-nav details[open] > summary { color: var(--color-gold); }
    .drawer-nav summary .nav-caret { margin-inline-start: auto; transition: transform .2s; }
    .drawer-nav details[open] > summary .nav-caret { transform: rotate(180deg); }
    .drawer-sub { padding: .25rem 0 .5rem; background: rgba(0,0,0,.12); border-radius: var(--radius); margin: .35rem 0; }
    .drawer-sub a { font-size: .9rem; padding: .6rem 2.4rem .6rem .5rem; border-bottom: none; color: rgba(255,255,255,.75); }
    .drawer-sub a:hover { color: var(--color-gold); }

    .drawer-section-title { font-size: .72rem; font-weight: 700; color: var(--color-gold); opacity: .8; margin: 1.5rem .5rem .35rem; letter-spacing: .3px; }
    .drawer-more { display: grid; grid-template-columns: 1fr 1fr; gap: .1rem .75rem; }
    .drawer-more a { padding: .5rem; font-size: .88rem; color: rgba(255,255,255,.7); }
    .drawer-more a:hover { color: var(--color-gold); }

    .drawer-account { margin-top: 1.25rem; padding: 1rem; border-radius: var(--radius-lg); background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08); }
    .drawer-user { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; }
    .drawer-user strong { display: block; font-size: .95rem; }
    .drawer-user small { color: rgba(255,255,255,.55); font-size: .75rem; }
    .drawer-account-links { display: grid; grid-template-columns: 1fr 1fr; gap: .4rem; }
    .drawer-account-links a, .drawer-account-links button {
        display: flex; align-items: center; gap: .45rem; padding: .6rem .7rem; border-radius: var(--radius);
        background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); font-size: .85rem;
        border: none; font-family: inherit; cursor: pointer; width: 100%;
    }
    .drawer-account-links i { color: var(--color-gold); width: 16px; }
    .drawer-account-links .logout { color: #FCA5A5; }
    .drawer-account-links .logout i { color: #FCA5A5; }
    .drawer-guest { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
    .drawer-guest .btn { justify-content: center; }
    .drawer-wa {
        display: flex; align-items: center; justify-content: center; gap: .5rem; margin-top: 1rem;
        padding: .8rem; border-radius: var(--radius); background: #25D366; color: #fff; font-weight: 700;
    }
    .drawer-wa:hover { color: #fff; filter: brightness(1.05); }

    /* ─── نقاط التحول ─── */
    @media (max-width: 1024px) {
        .header-nav, .account-name { display: none; }
        .menu-toggle { display: inline-block; }
        .header-actions { margin-inline-start: auto; }
    }
    @media (max-width: 640px) {
        .header-inner { padding: 0 1rem; gap: .5rem; }
        .site-logo { font-size: 1.3rem; }
        .header-auth, .hide-sm, .account-menu { display: none; }
        .header-icon { width: 38px; height: 38px; }
    }
    @media (min-width: 1025px) {
        .mobile-drawer, .drawer-backdrop { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mobile-drawer, .drawer-backdrop, .nav-dropdown { transition: none; }
    }
</style>

<header class="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="site-logo" aria-label="قطعة وقصة — الرئيسية">
            قطعة <span>وقصة</span>
        </a>

        {{-- ═══ القائمة الأفقية (كمبيوتر) ═══ --}}
        <nav class="header-nav" id="mainNav" aria-label="القائمة الرئيسية">
            @foreach($mainLinks as $link)
                @if(!empty($link['dropdown']))
                    <div class="nav-item has-dropdown">
                        <a href="{{ route($link['route']) }}" class="nav-link {{ $link['active'] ? 'active' : '' }}" aria-haspopup="true">
                            {{ $link['label'] }} <i class="fa-solid fa-chevron-down nav-caret" aria-hidden="true"></i>
                        </a>
                        <div class="nav-dropdown" role="menu">
                            <a href="{{ route('shop') }}" class="dd-strong">كل القطع <i class="fa-solid fa-arrow-left fa-xs" aria-hidden="true"></i></a>
                            <a href="{{ route('shop', ['rare' => 1]) }}">القطع النادرة <i class="fa-solid fa-gem fa-xs" style="color:var(--color-gold);" aria-hidden="true"></i></a>
                            @if($navCategories->isNotEmpty())
                                <div class="dd-sep"></div>
                                @foreach($navCategories as $cat)
                                    <a href="{{ route('shop', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                @endforeach
                            @endif
                            <div class="dd-sep"></div>
                            <a href="{{ route('collections') }}">المجموعات</a>
                        </div>
                    </div>
                @else
                    <div class="nav-item">
                        <a href="{{ route($link['route']) }}" class="nav-link {{ $link['active'] ? 'active' : '' }}" @if($link['active']) aria-current="page" @endif>{{ $link['label'] }}</a>
                    </div>
                @endif
            @endforeach
        </nav>

        {{-- ═══ الأزرار ═══ --}}
        <div class="header-actions">
            <button type="button" class="header-icon" id="searchToggle" aria-label="بحث" aria-controls="headerSearch" aria-expanded="false">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </button>

            @auth
                <a href="{{ route('account.wishlist') }}" class="header-icon hide-sm" aria-label="المفضلة" title="المفضلة">
                    <i class="fa-regular fa-heart" aria-hidden="true"></i>
                </a>
                <a href="{{ route('cart.index') }}" class="header-icon" aria-label="السلة ({{ $cartCount }})" title="السلة">
                    <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                </a>

                <div class="account-menu" id="accountMenu">
                    <button type="button" class="account-btn" id="accountToggle" aria-haspopup="true" aria-expanded="false" aria-controls="accountDropdown">
                        <span class="account-avatar">{{ mb_substr($user->name, 0, 1) }}</span>
                        <span class="account-name">{{ $user->name }}</span>
                        <i class="fa-solid fa-chevron-down nav-caret" aria-hidden="true"></i>
                    </button>
                    <div class="nav-dropdown account-dropdown" id="accountDropdown" role="menu">
                        <div class="dd-head">مرحباً <strong>{{ $user->name }}</strong></div>
                        <div class="dd-sep"></div>
                        @foreach($accountLinks as $a)
                            <a href="{{ route($a['route']) }}"><i class="fa-solid {{ $a['icon'] }} fa-sm" aria-hidden="true"></i> {{ $a['label'] }}</a>
                        @endforeach
                        @if($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge fa-sm" aria-hidden="true"></i> لوحة الإدارة</a>
                        @endif
                        <div class="dd-sep"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dd-logout"><i class="fa-solid fa-right-from-bracket fa-sm" aria-hidden="true"></i> تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="header-auth">
                    <a href="{{ route('login') }}" class="header-login">دخول</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">إنشاء حساب</a>
                </div>
            @endauth

            <button type="button" class="menu-toggle" id="menuToggle" aria-label="فتح القائمة" aria-controls="mobileDrawer" aria-expanded="false">
                <span aria-hidden="true"></span>
            </button>
        </div>
    </div>

    {{-- ═══ شريط البحث ═══ --}}
    <div class="header-search" id="headerSearch">
        <form action="{{ route('shop') }}" method="GET" class="search-form" role="search">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث عن قطعة، حقبة، أو مادة…" aria-label="بحث في المتجر">
            <button type="submit">بحث</button>
        </form>
    </div>
</header>

{{-- ═══ الدرج الجانبي (جوال/تابلت) ═══ --}}
<div class="drawer-backdrop" id="drawerBackdrop"></div>
<aside class="mobile-drawer" id="mobileDrawer" aria-label="القائمة" aria-hidden="true">
    <div class="drawer-head">
        <a href="{{ route('home') }}" class="site-logo">قطعة <span>وقصة</span></a>
        <button type="button" class="drawer-close" id="drawerClose" aria-label="إغلاق القائمة">&times;</button>
    </div>

    <div class="drawer-body">
        <form action="{{ route('shop') }}" method="GET" class="drawer-search" role="search">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="ابحث في المتجر…" aria-label="بحث في المتجر">
            <button type="submit" aria-label="بحث"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
        </form>

        <nav class="drawer-nav" aria-label="القائمة الرئيسية">
            @foreach($mainLinks as $link)
                @if(!empty($link['dropdown']))
                    <details {{ $link['active'] ? 'open' : '' }}>
                        <summary>
                            <i class="fa-solid {{ $link['icon'] }}" aria-hidden="true"></i> {{ $link['label'] }}
                            <i class="fa-solid fa-chevron-down nav-caret" aria-hidden="true"></i>
                        </summary>
                        <div class="drawer-sub">
                            <a href="{{ route('shop') }}">كل القطع</a>
                            <a href="{{ route('shop', ['rare' => 1]) }}">القطع النادرة</a>
                            @foreach($navCategories as $cat)
                                <a href="{{ route('shop', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                            @endforeach
                            <a href="{{ route('collections') }}">المجموعات</a>
                        </div>
                    </details>
                @else
                    <a href="{{ route($link['route']) }}" class="{{ $link['active'] ? 'active' : '' }}" @if($link['active']) aria-current="page" @endif>
                        <i class="fa-solid {{ $link['icon'] }}" aria-hidden="true"></i> {{ $link['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="drawer-section-title">معلومات</div>
        <div class="drawer-more">
            @foreach($moreLinks as $m)
                <a href="{{ route($m['route']) }}">{{ $m['label'] }}</a>
            @endforeach
        </div>

        <div class="drawer-account">
            @auth
                <div class="drawer-user">
                    <span class="account-avatar">{{ mb_substr($user->name, 0, 1) }}</span>
                    <div><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></div>
                </div>
                <div class="drawer-account-links">
                    @foreach($accountLinks as $a)
                        <a href="{{ route($a['route']) }}"><i class="fa-solid {{ $a['icon'] }} fa-sm" aria-hidden="true"></i> {{ $a['label'] }}</a>
                    @endforeach
                    <a href="{{ route('cart.index') }}"><i class="fa-solid fa-bag-shopping fa-sm" aria-hidden="true"></i> السلة @if($cartCount)({{ $cartCount }})@endif</a>
                    @if($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge fa-sm" aria-hidden="true"></i> لوحة الإدارة</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="grid-column:1 / -1;">
                        @csrf
                        <button type="submit" class="logout"><i class="fa-solid fa-right-from-bracket fa-sm" aria-hidden="true"></i> تسجيل الخروج</button>
                    </form>
                </div>
            @else
                <p style="font-size:.85rem;color:rgba(255,255,255,.7);margin-bottom:.75rem;">سجّل دخولك لمتابعة طلباتك وحفظ قطعك المفضلة</p>
                <div class="drawer-guest">
                    <a href="{{ route('login') }}" class="btn btn-outline">دخول</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">إنشاء حساب</a>
                </div>
            @endauth
        </div>

        @if($drawerWa = \App\Models\Setting::whatsappLink())
            <a href="{{ $drawerWa }}" class="drawer-wa" target="_blank" rel="noopener">
                <i class="fa-brands fa-whatsapp fa-lg" aria-hidden="true"></i> تواصل عبر واتساب
            </a>
        @endif
    </div>
</aside>

@push('scripts')
<script>
(function () {
    const root      = document.documentElement;
    const toggle    = document.getElementById('menuToggle');
    const drawer    = document.getElementById('mobileDrawer');
    const backdrop  = document.getElementById('drawerBackdrop');
    const closeBtn  = document.getElementById('drawerClose');
    const searchBtn = document.getElementById('searchToggle');
    const searchBar = document.getElementById('headerSearch');
    const accMenu   = document.getElementById('accountMenu');
    const accBtn    = document.getElementById('accountToggle');

    // ─── الدرج الجانبي ───
    const setDrawer = (open) => {
        root.classList.toggle('drawer-open', open);
        toggle.setAttribute('aria-expanded', open);
        drawer.setAttribute('aria-hidden', !open);
        if (open) closeBtn.focus(); else if (document.activeElement && drawer.contains(document.activeElement)) toggle.focus();
    };
    toggle.addEventListener('click', () => setDrawer(true));
    closeBtn.addEventListener('click', () => setDrawer(false));
    backdrop.addEventListener('click', () => setDrawer(false));
    window.addEventListener('resize', () => { if (window.innerWidth > 1024) setDrawer(false); });

    // ─── البحث ───
    searchBtn.addEventListener('click', () => {
        // على الجوال البحث موجود داخل الدرج
        if (window.innerWidth <= 1024) {
            setDrawer(true);
            setTimeout(() => drawer.querySelector('input[type=search]').focus(), 250);
            return;
        }
        const open = !searchBar.classList.contains('open');
        searchBar.classList.toggle('open', open);
        searchBtn.setAttribute('aria-expanded', open);
        if (open) searchBar.querySelector('input').focus();
    });

    // ─── قائمة الحساب ───
    const setAccount = (open) => {
        if (!accMenu) return;
        accMenu.classList.toggle('open', open);
        accBtn.setAttribute('aria-expanded', open);
    };
    accBtn?.addEventListener('click', (e) => { e.stopPropagation(); setAccount(!accMenu.classList.contains('open')); });
    document.addEventListener('click', (e) => { if (accMenu && !accMenu.contains(e.target)) setAccount(false); });

    // ─── Escape يغلق أي شيء مفتوح ───
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        setDrawer(false); setAccount(false);
        searchBar.classList.remove('open'); searchBtn.setAttribute('aria-expanded', false);
    });
})();
</script>
@endpush
