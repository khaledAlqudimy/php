@extends('layouts.app')
@section('title', 'المتجر')
@section('meta_description', 'تسوّق قطع الأنتيك الأصيلة: أثاث كلاسيكي، ساعات قديمة، ثريات، فضيات ونحاسيات ولوحات — مع شحن لجميع مناطق المملكة.')

@push('styles')
<style>
/* ══ Shop Layout ══ */
.shop-page { padding: 2.5rem 0 4rem; }

.shop-inner {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2rem;
    align-items: start;
}

/* ══ Page Header ══ */
.shop-header {
    background: var(--color-brown);
    padding: 3rem 0 2.5rem;
    margin-bottom: 0;
}

.shop-header .container { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.shop-header-title { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); }
.shop-breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: rgba(255,255,255,.5); }
.shop-breadcrumb a { color: rgba(255,255,255,.6); }
.shop-breadcrumb a:hover { color: var(--color-gold); }
.shop-breadcrumb span { color: rgba(255,255,255,.3); }

/* ══ Sidebar ══ */
.shop-sidebar {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--color-border);
    position: sticky;
    top: 88px;
}

.filter-section { margin-bottom: 1.75rem; }
.filter-section:last-child { margin-bottom: 0; }

.filter-title {
    font-size: .8rem;
    font-weight: 700;
    color: var(--color-brown);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: .5rem;
}

.filter-title::after {
    content: ''; flex: 1; height: 1px; background: var(--color-border);
}

/* ─── Search ─── */
.search-wrap { position: relative; }
.search-input {
    width: 100%;
    padding: .7rem 1rem .7rem 2.75rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .875rem;
    background: var(--color-bg);
    color: var(--color-text);
    direction: rtl;
    transition: var(--transition);
}
.search-input:focus { outline: none; border-color: var(--color-gold); background: #fff; box-shadow: 0 0 0 3px rgba(182,138,74,.12); }
.search-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: .85rem; }

/* ─── Categories ─── */
.cat-list { list-style: none; }
.cat-list li a {
    display: flex; align-items: center; justify-content: space-between;
    padding: .5rem .6rem;
    border-radius: var(--radius);
    font-size: .875rem;
    color: var(--color-text-muted);
    transition: var(--transition);
}
.cat-list li a:hover,
.cat-list li a.active {
    background: rgba(182,138,74,.08);
    color: var(--color-gold);
    font-weight: 600;
}
.cat-count {
    background: var(--color-bg-2);
    color: var(--color-text-muted);
    font-size: .7rem;
    padding: .1rem .45rem;
    border-radius: 100px;
}

/* ─── Price Range ─── */
.price-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-top: .75rem; }
.price-input {
    width: 100%;
    padding: .55rem .75rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .8rem;
    text-align: center;
    background: var(--color-bg);
    color: var(--color-text);
}
.price-input:focus { outline: none; border-color: var(--color-gold); }

/* ─── Checkboxes ─── */
.filter-check {
    display: flex; align-items: center; gap: .6rem;
    padding: .4rem 0;
    font-size: .875rem;
    color: var(--color-text-muted);
    cursor: pointer;
}
.filter-check input { accent-color: var(--color-gold); width: 15px; height: 15px; }
.filter-check:hover { color: var(--color-text); }

/* ─── Reset Button ─── */
.btn-reset-filters {
    width: 100%;
    padding: .65rem;
    background: var(--color-bg-2);
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .875rem;
    color: var(--color-text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.btn-reset-filters:hover { border-color: var(--color-gold); color: var(--color-gold); background: rgba(182,138,74,.05); }

/* ═══ Products Area ═══ */
.products-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    margin-bottom: 1.5rem;
}

.products-count {
    font-size: .875rem;
    color: var(--color-text-muted);
}

.products-count strong { color: var(--color-brown); }

.sort-select {
    padding: .55rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .875rem;
    color: var(--color-text);
    background: var(--color-white);
    cursor: pointer;
}

.sort-select:focus { outline: none; border-color: var(--color-gold); }

/* Active Filters Chips */
.active-filters {
    display: flex; flex-wrap: wrap; gap: .5rem;
    margin-bottom: 1rem;
}

.filter-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    background: rgba(182,138,74,.1);
    border: 1px solid rgba(182,138,74,.25);
    color: var(--color-gold);
    font-size: .75rem; font-weight: 600;
    padding: .25rem .7rem;
    border-radius: 100px;
}

.filter-chip a { color: inherit; font-size: .8rem; line-height: 1; }
.filter-chip a:hover { opacity: .7; }

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1.25rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 5rem 2rem;
    color: var(--color-text-muted);
}

.empty-state i { font-size: 3rem; opacity: .3; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1.1rem; color: var(--color-brown); margin-bottom: .5rem; }

/* Pagination */
.pagination-wrap { margin-top: 2.5rem; display: flex; justify-content: center; }

/* Mobile */
.mobile-filter-toggle {
    display: none;
    width: 100%;
    padding: .75rem 1rem;
    background: var(--color-white);
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .9rem; font-weight: 600;
    color: var(--color-brown);
    cursor: pointer;
    margin-bottom: 1rem;
    align-items: center; gap: .5rem; justify-content: center;
}

@media (max-width: 900px) {
    .shop-inner { grid-template-columns: 1fr; }
    .shop-sidebar {
        position: static;
        display: none;
    }
    .shop-sidebar.open { display: block; }
    .mobile-filter-toggle { display: flex; }
}
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="shop-header">
    <div class="container">
        <h1 class="shop-header-title">المتجر</h1>
        <nav class="shop-breadcrumb">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span>/</span>
            <span style="color:rgba(255,255,255,.8)">المتجر</span>
        </nav>
    </div>
</div>

<div class="shop-page">
    <div class="container">

        {{-- ── Mobile Filter Toggle ── --}}
        <button class="mobile-filter-toggle" onclick="document.querySelector('.shop-sidebar').classList.toggle('open')">
            <i class="fa-solid fa-sliders"></i> الفلاتر
            @if(request()->hasAny(['q','category','min_price','max_price','rare','featured']))
                <span style="background:var(--color-gold);color:var(--color-brown);border-radius:100px;padding:.1rem .45rem;font-size:.7rem;">مفعّلة</span>
            @endif
        </button>

        <div class="shop-inner">

            {{-- ══ Sidebar ══ --}}
            <form id="filterForm" method="GET" action="{{ route('shop') }}">
                <aside class="shop-sidebar">

                    {{-- البحث --}}
                    <div class="filter-section">
                        <div class="filter-title">بحث</div>
                        <div class="search-wrap">
                            <input
                                type="text" name="q"
                                class="search-input"
                                placeholder="ابحث عن قطعة..."
                                value="{{ request('q') }}"
                            >
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        </div>
                    </div>

                    {{-- التصنيفات --}}
                    <div class="filter-section">
                        <div class="filter-title">التصنيف</div>
                        <ul class="cat-list">
                            <li>
                                <a href="{{ route('shop', array_merge(request()->except('category'), [])) }}"
                                   class="{{ ! request('category') ? 'active' : '' }}">
                                    جميع التصنيفات
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                                       class="{{ request('category') === $cat->slug ? 'active' : '' }}">
                                        {{ $cat->name }}
                                        <span class="cat-count">{{ $cat->products()->active()->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- السعر --}}
                    <div class="filter-section">
                        <div class="filter-title">نطاق السعر</div>
                        <div class="price-inputs">
                            <input
                                type="number" name="min_price"
                                class="price-input"
                                placeholder="من {{ number_format($priceRange['min'], 0) }}"
                                value="{{ request('min_price') }}"
                                min="0"
                            >
                            <input
                                type="number" name="max_price"
                                class="price-input"
                                placeholder="إلى {{ number_format($priceRange['max'], 0) }}"
                                value="{{ request('max_price') }}"
                                min="0"
                            >
                        </div>
                    </div>

                    {{-- خصائص خاصة --}}
                    <div class="filter-section">
                        <div class="filter-title">خصائص خاصة</div>
                        <label class="filter-check">
                            <input type="checkbox" name="rare" value="1" {{ request('rare') ? 'checked' : '' }}>
                            القطع النادرة فقط
                        </label>
                        <label class="filter-check">
                            <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}>
                            المنتجات المميزة
                        </label>
                    </div>

                    {{-- الترتيب --}}
                    <div class="filter-section">
                        <div class="filter-title">الترتيب</div>
                        <select name="sort" class="sort-select" style="width:100%;" onchange="document.getElementById('filterForm').submit()">
                            <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected' : '' }}>الأحدث</option>
                            <option value="oldest"     {{ request('sort') === 'oldest'               ? 'selected' : '' }}>الأقدم</option>
                            <option value="price_asc"  {{ request('sort') === 'price_asc'            ? 'selected' : '' }}>السعر: من الأقل</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc'           ? 'selected' : '' }}>السعر: من الأعلى</option>
                        </select>
                    </div>

                    {{-- أزرار --}}
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:.5rem;">
                        <i class="fa-solid fa-filter fa-sm"></i> تطبيق الفلاتر
                    </button>
                    <a href="{{ route('shop') }}" class="btn-reset-filters">
                        <i class="fa-solid fa-rotate-left fa-sm"></i> إعادة ضبط
                    </a>
                </aside>
            </form>

            {{-- ══ Products Area ══ --}}
            <div>
                {{-- Active Filters --}}
                @php $hasFilters = request()->hasAny(['q','category','min_price','max_price','rare','featured']); @endphp
                @if($hasFilters)
                    <div class="active-filters">
                        @if(request('q'))
                            <div class="filter-chip">
                                <i class="fa-solid fa-magnifying-glass fa-xs"></i>
                                {{ request('q') }}
                                <a href="{{ route('shop', request()->except('q')) }}">✕</a>
                            </div>
                        @endif
                        @if(request('category'))
                            <div class="filter-chip">
                                <i class="fa-solid fa-tag fa-xs"></i>
                                {{ $categories->firstWhere('slug', request('category'))?->name ?? request('category') }}
                                <a href="{{ route('shop', request()->except('category')) }}">✕</a>
                            </div>
                        @endif
                        @if(request('min_price') || request('max_price'))
                            <div class="filter-chip">
                                <i class="fa-solid fa-coins fa-xs"></i>
                                {{ request('min_price') ?? '0' }} — {{ request('max_price') ?? '∞' }} ر.س
                                <a href="{{ route('shop', request()->except(['min_price','max_price'])) }}">✕</a>
                            </div>
                        @endif
                        @if(request('rare'))
                            <div class="filter-chip">
                                <i class="fa-solid fa-gem fa-xs"></i> نادرة
                                <a href="{{ route('shop', request()->except('rare')) }}">✕</a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Toolbar --}}
                <div class="products-toolbar">
                    <p class="products-count">
                        عرض <strong>{{ $products->count() }}</strong>
                        من <strong>{{ $products->total() }}</strong> قطعة
                    </p>
                </div>

                {{-- Grid --}}
                <div class="products-grid">
                    @forelse($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div class="empty-state">
                            <i class="fa-solid fa-box-open"></i>
                            <h3>لا توجد نتائج</h3>
                            <p>جرّب تعديل الفلاتر أو البحث بكلمات مختلفة</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="pagination-wrap">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// تطبيق الفلاتر عند الكتابة (debounce)
let searchTimer;
document.querySelector('input[name="q"]').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 600);
});
</script>
@endpush
