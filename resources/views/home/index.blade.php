@extends('layouts.app')
@section('title', 'الرئيسية')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   الصفحة الرئيسية — قطعة وقصة
   ══════════════════════════════════════════════════════════════ */

/* ─── Section Helpers ─── */
.section { padding: 5rem 0; }
.section-alt { background: var(--color-bg-2); }
.section-dark { background: var(--color-brown); color: #fff; }

.section-header { text-align: center; margin-bottom: 3rem; }
.section-label {
    display: inline-block;
    color: var(--color-gold);
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: .75rem;
}
.section-title {
    font-size: clamp(1.6rem, 3vw, 2.25rem);
    font-weight: 700;
    color: var(--color-brown);
    margin-bottom: .75rem;
    line-height: 1.3;
}
.section-dark .section-title { color: var(--color-gold); }
.section-subtitle { color: var(--color-text-muted); font-size: 1rem; max-width: 560px; margin: 0 auto; }
.section-dark .section-subtitle { color: rgba(255,255,255,.65); }

.section-line {
    display: flex; align-items: center; justify-content: center; gap: .75rem;
    margin-top: 1rem;
}
.section-line::before, .section-line::after {
    content: ''; width: 48px; height: 1px; background: var(--color-gold); opacity: .5;
}
.section-line-diamond {
    width: 8px; height: 8px; background: var(--color-gold); transform: rotate(45deg);
}

/* ══════════════════════════════════════════════════════════════
   1. Hero
   ══════════════════════════════════════════════════════════════ */
.hero {
    background: var(--color-brown);
    min-height: 88vh;
    display: flex; align-items: center;
    position: relative;
    overflow: hidden;
}

/* زخرفة دوائر خلفية */
.hero::before {
    content: '';
    position: absolute; top: -120px; left: -120px;
    width: 500px; height: 500px;
    border: 80px solid rgba(182,138,74,.06);
    border-radius: 50%;
}
.hero::after {
    content: '';
    position: absolute; bottom: -80px; right: -80px;
    width: 350px; height: 350px;
    border: 60px solid rgba(182,138,74,.05);
    border-radius: 50%;
}

.hero-inner {
    max-width: 1280px; margin: 0 auto;
    padding: 4rem 1.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    position: relative; z-index: 1;
}

.hero-content { color: #fff; }

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(182,138,74,.15);
    border: 1px solid rgba(182,138,74,.3);
    color: var(--color-gold);
    font-size: .8rem;
    font-weight: 600;
    padding: .35rem 1rem;
    border-radius: 100px;
    margin-bottom: 1.5rem;
    letter-spacing: .5px;
}

.hero-title {
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.25rem;
}

.hero-title .highlight {
    color: var(--color-gold);
    position: relative;
}

.hero-desc {
    font-size: 1.05rem;
    color: rgba(255,255,255,.72);
    line-height: 1.8;
    margin-bottom: 2.5rem;
    max-width: 460px;
}

.hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

.hero-btn-primary {
    padding: .875rem 2rem;
    background: var(--color-gold);
    color: var(--color-brown);
    font-weight: 700;
    font-size: 1rem;
    border-radius: var(--radius);
    transition: var(--transition);
    display: inline-flex; align-items: center; gap: .5rem;
}
.hero-btn-primary:hover {
    background: var(--color-gold-2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(182,138,74,.35);
}

.hero-btn-secondary {
    padding: .875rem 2rem;
    background: transparent;
    border: 1.5px solid rgba(255,255,255,.3);
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    border-radius: var(--radius);
    transition: var(--transition);
}
.hero-btn-secondary:hover {
    border-color: var(--color-gold);
    color: var(--color-gold);
}

/* الإطار الزخرفي على اليمين */
.hero-frame {
    position: relative;
    aspect-ratio: 4/5;
    max-height: 520px;
}

.hero-frame-border {
    position: absolute;
    inset: 0;
    border: 2px solid rgba(182,138,74,.4);
    border-radius: var(--radius-lg);
}

/* زوايا ذهبية */
.hero-frame-corner {
    position: absolute;
    width: 28px; height: 28px;
}
.hero-frame-corner::before,
.hero-frame-corner::after { content: ''; position: absolute; background: var(--color-gold); }
.hero-frame-corner::before { width: 100%; height: 2px; }
.hero-frame-corner::after  { width: 2px; height: 100%; }

.hero-frame-corner--tl { top: -2px; right: -2px; }
.hero-frame-corner--tl::before { top: 0; right: 0; }
.hero-frame-corner--tl::after  { top: 0; right: 0; }

.hero-frame-corner--tr { top: -2px; left: -2px; }
.hero-frame-corner--tr::before { top: 0; left: 0; }
.hero-frame-corner--tr::after  { top: 0; left: 0; }

.hero-frame-corner--bl { bottom: -2px; right: -2px; }
.hero-frame-corner--bl::before { bottom: 0; right: 0; }
.hero-frame-corner--bl::after  { bottom: 0; right: 0; }

.hero-frame-corner--br { bottom: -2px; left: -2px; }
.hero-frame-corner--br::before { bottom: 0; left: 0; }
.hero-frame-corner--br::after  { bottom: 0; left: 0; }

.hero-frame-inner {
    position: absolute;
    inset: 16px;
    background: rgba(255,255,255,.04);
    border-radius: var(--radius);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 1rem;
}

.hero-frame-icon {
    width: 80px; height: 80px;
    background: rgba(182,138,74,.15);
    border: 1px solid rgba(182,138,74,.3);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-gold);
    font-size: 2rem;
}

.hero-frame-text {
    color: rgba(255,255,255,.6);
    font-size: .875rem;
    text-align: center;
    line-height: 1.7;
    padding: 0 1.5rem;
}

.hero-stats {
    display: flex; gap: 2rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,.1);
}

.hero-stat-number {
    font-size: 1.75rem; font-weight: 700; color: var(--color-gold);
}
.hero-stat-label { font-size: .8rem; color: rgba(255,255,255,.5); }

/* ══════════════════════════════════════════════════════════════
   2. Category Strip
   ══════════════════════════════════════════════════════════════ */
.categories-strip { padding: 3.5rem 0; background: var(--color-white); }

.categories-grid {
    display: flex; gap: 1.5rem;
    justify-content: center; flex-wrap: wrap;
}

.category-item {
    display: flex; flex-direction: column; align-items: center; gap: .75rem;
    text-align: center; cursor: pointer;
    transition: var(--transition);
}

.category-item:hover { transform: translateY(-4px); }

.category-circle {
    width: 88px; height: 88px;
    border-radius: 50%;
    background: var(--color-bg);
    border: 2px solid var(--color-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
    transition: var(--transition);
    position: relative;
}

.category-item:hover .category-circle {
    border-color: var(--color-gold);
    background: rgba(182,138,74,.08);
    box-shadow: 0 0 0 4px rgba(182,138,74,.12);
}

.category-name {
    font-size: .8rem; font-weight: 600;
    color: var(--color-brown);
    max-width: 90px; line-height: 1.3;
}

/* ══════════════════════════════════════════════════════════════
   3. Products Grid
   ══════════════════════════════════════════════════════════════ */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--color-border);
    transition: var(--transition);
    display: flex; flex-direction: column;
    text-decoration: none; color: inherit;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-gold);
}

.product-card__image-wrap {
    aspect-ratio: 4/3;
    position: relative; overflow: hidden;
    background: var(--color-bg-2);
}

.product-card__img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s ease;
}

.product-card:hover .product-card__img { transform: scale(1.06); }

.product-card__placeholder {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: .5rem;
    color: var(--color-text-muted);
    font-size: .8rem; font-weight: 600;
    letter-spacing: 1px;
}

.product-card__badges {
    position: absolute; top: .75rem; right: .75rem;
    display: flex; flex-direction: column; gap: .3rem;
    z-index: 2;
}

.badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 100px;
    font-size: .7rem; font-weight: 700;
}

.badge--rare    { background: #7C3AED; color: #fff; }
.badge--featured { background: var(--color-gold); color: var(--color-brown); }
.badge--sale    { background: #DC2626; color: #fff; }

.wishlist-btn {
    position: absolute; top: .75rem; left: .75rem;
    width: 34px; height: 34px;
    background: rgba(255,255,255,.9);
    border: none; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--color-text-muted);
    transition: var(--transition); z-index: 2;
    backdrop-filter: blur(4px);
}
.wishlist-btn:hover { color: #DC2626; background: #fff; transform: scale(1.1); }
.wishlist-btn.active i { font-weight: 900; color: #DC2626; }

.product-card__overlay {
    position: absolute; inset: 0;
    background: rgba(58,36,24,.55);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .3s;
}
.product-card:hover .product-card__overlay { opacity: 1; }

.overlay-btn {
    background: var(--color-gold);
    color: var(--color-brown);
    padding: .5rem 1.25rem;
    border-radius: var(--radius);
    font-size: .875rem; font-weight: 700;
    display: flex; align-items: center; gap: .4rem;
}

.product-card__body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; gap: .4rem; }

.product-card__category {
    font-size: .72rem; font-weight: 600;
    color: var(--color-gold); text-transform: uppercase; letter-spacing: .5px;
}

.product-card__title {
    font-size: .95rem; font-weight: 700;
    color: var(--color-brown); line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

.product-card__meta {
    display: flex; gap: .75rem; flex-wrap: wrap;
    font-size: .75rem; color: var(--color-text-muted);
}

.product-card__footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto; padding-top: .75rem;
    border-top: 1px solid var(--color-border);
}

.price-current { font-size: 1rem; font-weight: 700; color: var(--color-brown); }
.price-old {
    font-size: .8rem; color: var(--color-text-muted);
    text-decoration: line-through; margin-right: .3rem;
}

.stock-badge {
    font-size: .7rem; font-weight: 600; padding: .2rem .6rem; border-radius: 100px;
}
.stock-in  { background: #DCFCE7; color: #166534; }
.stock-out { background: #FEE2E2; color: #991B1B; }

/* ══════════════════════════════════════════════════════════════
   4. RareFinds — خاص
   ══════════════════════════════════════════════════════════════ */
.rare-finds { background: var(--color-brown); position: relative; overflow: hidden; }

.rare-finds::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23B68A4A' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.rare-finds .section-header { position: relative; z-index: 1; }

.rare-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
    position: relative; z-index: 1;
}

.rare-card {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(182,138,74,.25);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: var(--transition);
    text-decoration: none; color: inherit;
    display: flex; flex-direction: column;
}

.rare-card:hover {
    border-color: var(--color-gold);
    background: rgba(255,255,255,.08);
    transform: translateY(-4px);
}

.rare-card__img {
    aspect-ratio: 4/3;
    background: rgba(255,255,255,.03);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}

.rare-card__img img { width: 100%; height: 100%; object-fit: cover; }

.rare-card__img-placeholder {
    display: flex; flex-direction: column; align-items: center; gap: .5rem;
    color: rgba(255,255,255,.25); font-size: .8rem;
}

.rare-badge {
    position: absolute; top: .75rem; right: .75rem;
    background: linear-gradient(135deg, #7C3AED, #5B21B6);
    color: #fff; font-size: .7rem; font-weight: 700;
    padding: .25rem .75rem; border-radius: 100px;
    display: flex; align-items: center; gap: .3rem;
}

.rare-card__body { padding: 1.1rem; }
.rare-card__cat  { font-size: .72rem; color: var(--color-gold); font-weight: 600; text-transform: uppercase; margin-bottom: .3rem; }
.rare-card__name { font-size: .95rem; font-weight: 700; color: #fff; margin-bottom: .5rem; line-height: 1.4; }
.rare-card__price { color: var(--color-gold); font-weight: 700; font-size: 1rem; }

/* ══════════════════════════════════════════════════════════════
   5. Promo Banners
   ══════════════════════════════════════════════════════════════ */
.promo-banners { padding: 5rem 0; }

.promo-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.promo-card {
    border-radius: var(--radius-lg);
    padding: 3rem 2.5rem;
    position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    min-height: 260px;
    transition: var(--transition);
    text-decoration: none;
}
.promo-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

.promo-card--auction  { background: linear-gradient(135deg, #3A2418 0%, #1A0F08 100%); }
.promo-card--shopper  { background: linear-gradient(135deg, #1A2A3A 0%, #0A1520 100%); }

.promo-card::before {
    content: '';
    position: absolute; bottom: -60px; left: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    border: 40px solid rgba(182,138,74,.08);
}

.promo-card__label {
    color: var(--color-gold); font-size: .75rem;
    font-weight: 700; letter-spacing: 2px;
    text-transform: uppercase; margin-bottom: .75rem;
}

.promo-card__title {
    font-size: 1.5rem; font-weight: 700;
    color: #fff; margin-bottom: .75rem; line-height: 1.3;
}

.promo-card__desc {
    color: rgba(255,255,255,.6); font-size: .9rem;
    line-height: 1.7; margin-bottom: 1.5rem; flex: 1;
}

.promo-card__btn {
    display: inline-flex; align-items: center; gap: .5rem;
    background: var(--color-gold); color: var(--color-brown);
    font-weight: 700; font-size: .9rem;
    padding: .65rem 1.5rem; border-radius: var(--radius);
    transition: var(--transition); align-self: flex-start;
}
.promo-card:hover .promo-card__btn {
    background: var(--color-gold-2); transform: translateX(-4px);
}

.promo-icon {
    position: absolute; top: 1.5rem; left: 2rem;
    font-size: 4rem; opacity: .06; color: var(--color-gold);
}

/* ══════════════════════════════════════════════════════════════
   6. Testimonials
   ══════════════════════════════════════════════════════════════ */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.testimonial-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    border: 1px solid var(--color-border);
    position: relative;
}

.testimonial-card::before {
    content: '"';
    position: absolute; top: 1rem; right: 1.5rem;
    font-size: 4rem; color: var(--color-gold);
    opacity: .2; line-height: 1; font-family: Georgia, serif;
}

.testimonial-stars {
    color: var(--color-gold); font-size: .85rem;
    margin-bottom: .75rem; display: flex; gap: .1rem;
}

.testimonial-text {
    color: var(--color-text-muted); font-size: .9rem;
    line-height: 1.8; margin-bottom: 1.25rem;
}

.testimonial-author {
    display: flex; align-items: center; gap: .75rem;
}

.testimonial-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--color-gold);
    display: flex; align-items: center; justify-content: center;
    color: var(--color-brown); font-weight: 700; font-size: .875rem;
}

.testimonial-name { font-weight: 700; font-size: .875rem; color: var(--color-brown); }
.testimonial-loc  { font-size: .75rem; color: var(--color-text-muted); }

/* ══════════════════════════════════════════════════════════════
   7. Trust Badges
   ══════════════════════════════════════════════════════════════ */
.trust-badges { padding: 4rem 0; background: var(--color-white); }

.trust-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 2rem;
}

.trust-item {
    display: flex; flex-direction: column; align-items: center;
    gap: .75rem; text-align: center;
}

.trust-icon {
    width: 64px; height: 64px;
    background: rgba(182,138,74,.1);
    border: 1.5px solid rgba(182,138,74,.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-gold); font-size: 1.5rem;
}

.trust-name  { font-weight: 700; font-size: .875rem; color: var(--color-brown); }
.trust-desc  { font-size: .78rem; color: var(--color-text-muted); line-height: 1.5; }

/* ══════════════════════════════════════════════════════════════
   8. View All Button
   ══════════════════════════════════════════════════════════════ */
.view-all-wrap { text-align: center; margin-top: 2.5rem; }

.btn-view-all {
    display: inline-flex; align-items: center; gap: .6rem;
    padding: .8rem 2rem;
    border: 2px solid var(--color-gold);
    color: var(--color-gold);
    border-radius: var(--radius);
    font-weight: 700; font-size: .95rem;
    transition: var(--transition);
}
.btn-view-all:hover {
    background: var(--color-gold); color: var(--color-brown);
    transform: translateY(-2px);
}

/* ══════════════════════════════════════════════════════════════
   Responsive
   ══════════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .hero-inner { grid-template-columns: 1fr; gap: 2rem; }
    .hero-frame { display: none; }
    .hero-stats { gap: 1.5rem; }
    .promo-grid { grid-template-columns: 1fr; }
    .section { padding: 3.5rem 0; }
}

@media (max-width: 480px) {
    .products-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .categories-grid { gap: 1rem; }
    .category-circle { width: 72px; height: 72px; font-size: 1.4rem; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     1. Hero
     ══════════════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <i class="fa-solid fa-gem fa-xs"></i>
                معرض الأنتيك الفاخر
            </div>
            <h1 class="hero-title">
                كل قطعة تحمل <br>
                <span class="highlight">روحاً</span> وتروي
                <span class="highlight">قصة</span>
            </h1>
            <p class="hero-desc">
                اكتشف مجموعة استثنائية من التحف والأثريات النادرة،
                كل قطعة منتقاة بعناية لتحمل معها إرثاً من الجمال والتاريخ.
            </p>
            <div class="hero-actions">
                <a href="{{ route('shop') }}" class="hero-btn-primary">
                    تصفح المتجر <i class="fa-solid fa-arrow-left fa-sm"></i>
                </a>
                <a href="{{ route('auction.index') }}" class="hero-btn-secondary">
                    المزادات الحصرية
                </a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-number">+500</div>
                    <div class="hero-stat-label">قطعة أصيلة</div>
                </div>
                <div>
                    <div class="hero-stat-number">+15</div>
                    <div class="hero-stat-label">حقبة تاريخية</div>
                </div>
                <div>
                    <div class="hero-stat-number">+30</div>
                    <div class="hero-stat-label">دولة حول العالم</div>
                </div>
            </div>
        </div>

        {{-- الإطار الزخرفي --}}
        <div class="hero-frame">
            <div class="hero-frame-border"></div>
            <span class="hero-frame-corner hero-frame-corner--tl"></span>
            <span class="hero-frame-corner hero-frame-corner--tr"></span>
            <span class="hero-frame-corner hero-frame-corner--bl"></span>
            <span class="hero-frame-corner hero-frame-corner--br"></span>
            <div class="hero-frame-inner">
                <div class="hero-frame-icon">
                    <i class="fa-solid fa-archway"></i>
                </div>
                <p class="hero-frame-text">
                    قطع نادرة من مختلف أنحاء العالم<br>
                    تحمل قصصاً لا تُنسى
                </p>
                <div style="display:flex;gap:.5rem;">
                    @foreach(['fa-gem','fa-scroll','fa-landmark','fa-coins'] as $icon)
                        <div style="width:36px;height:36px;background:rgba(182,138,74,.1);border:1px solid rgba(182,138,74,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--color-gold);font-size:.85rem;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     2. Category Strip
     ══════════════════════════════════════════════════════════════ --}}
<section class="categories-strip">
    <div class="container">
        <div class="categories-grid">
            @forelse($categories as $category)
                @php
                    $icons = ['🏺','🕰️','🪑','💡','🥈','🖼️'];
                    $icon  = $icons[$loop->index % count($icons)];
                @endphp
                <a href="{{ route('shop') }}?category={{ $category->slug }}" class="category-item">
                    <div class="category-circle">{{ $icon }}</div>
                    <span class="category-name">{{ $category->name }}</span>
                </a>
            @empty
                @foreach(['الأثاث','التحف','الساعات','الإضاءة','الفضيات','اللوحات'] as $i => $name)
                    <div class="category-item">
                        <div class="category-circle">{{ ['🏺','🕰️','🪑','💡','🥈','🖼️'][$i] }}</div>
                        <span class="category-name">{{ $name }}</span>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     3. Latest Products
     ══════════════════════════════════════════════════════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">وصل حديثاً</span>
            <h2 class="section-title">أحدث القطع</h2>
            <p class="section-subtitle">تشكيلة منتقاة بعناية من أحدث ما وصل إلى معرضنا</p>
            <div class="section-line"><div class="section-line-diamond"></div></div>
        </div>

        @if($latestProducts->isNotEmpty())
            <div class="products-grid">
                @foreach($latestProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <p style="text-align:center;color:var(--color-text-muted);padding:3rem 0;">
                لم يتم إضافة منتجات بعد.
            </p>
        @endif

        <div class="view-all-wrap">
            <a href="{{ route('shop') }}" class="btn-view-all">
                عرض جميع القطع <i class="fa-solid fa-arrow-left fa-sm"></i>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     4. Promo Banners
     ══════════════════════════════════════════════════════════════ --}}
<section class="promo-banners section-alt section">
    <div class="container">
        <div class="promo-grid">
            <a href="{{ route('auction.index') }}" class="promo-card promo-card--auction">
                <i class="fa-solid fa-gavel promo-icon"></i>
                <div class="promo-card__label">حصري</div>
                <h3 class="promo-card__title">مزادات القطع النادرة</h3>
                <p class="promo-card__desc">
                    شارك في مزاداتنا الحصرية على أنفس القطع الأثرية والتحف النادرة.
                    فرصتك للحصول على قطعة لا تُقدر بثمن.
                </p>
                <span class="promo-card__btn">
                    سجّل اهتمامك <i class="fa-solid fa-arrow-left fa-xs"></i>
                </span>
            </a>
            <a href="{{ route('personal-shopper.index') }}" class="promo-card promo-card--shopper">
                <i class="fa-solid fa-magnifying-glass promo-icon"></i>
                <div class="promo-card__label">خدمة مميزة</div>
                <h3 class="promo-card__title">الباحث الشخصي</h3>
                <p class="promo-card__desc">
                    هل تبحث عن قطعة بعينها؟ خبراؤنا يبحثون عنها لك في
                    أرجاء العالم حتى يجدوها. خدمة شخصية لعشاق الاقتناء.
                </p>
                <span class="promo-card__btn">
                    ابدأ البحث <i class="fa-solid fa-arrow-left fa-xs"></i>
                </span>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     5. Featured Products
     ══════════════════════════════════════════════════════════════ --}}
@if($featuredProducts->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">مختارات المعرض</span>
            <h2 class="section-title">القطع المميزة</h2>
            <p class="section-subtitle">منتقاة بعناية من أجمل ما في مجموعتنا</p>
            <div class="section-line"><div class="section-line-diamond"></div></div>
        </div>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     6. Rare Finds
     ══════════════════════════════════════════════════════════════ --}}
@if($rareProducts->isNotEmpty())
<section class="section rare-finds">
    <div class="container">
        <div class="section-header">
            <span class="section-label" style="color:var(--color-gold);">نادرة وحصرية</span>
            <h2 class="section-title">القطع النادرة</h2>
            <p class="section-subtitle">مجموعة استثنائية من أندر ما يمكن اقتناؤه</p>
            <div class="section-line"><div class="section-line-diamond"></div></div>
        </div>
        <div class="rare-grid">
            @foreach($rareProducts as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="rare-card">
                    <div class="rare-card__img">
                        @if($product->mainImage())
                            <img src="{{ Storage::url($product->mainImage()) }}" alt="{{ $product->title }}">
                        @else
                            <div class="rare-card__img-placeholder">
                                <i class="fa-solid fa-gem fa-2x"></i>
                                <span>قطعة نادرة</span>
                            </div>
                        @endif
                        <div class="rare-badge">
                            <i class="fa-solid fa-gem fa-xs"></i> نادرة
                        </div>
                    </div>
                    <div class="rare-card__body">
                        @if($product->category)
                            <div class="rare-card__cat">{{ $product->category->name }}</div>
                        @endif
                        <div class="rare-card__name">{{ $product->title }}</div>
                        <div class="rare-card__price">{{ number_format($product->price, 0) }} ر.س</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     7. Testimonials
     ══════════════════════════════════════════════════════════════ --}}
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-label">آراء عملائنا</span>
            <h2 class="section-title">يثقون بنا</h2>
            <div class="section-line"><div class="section-line-diamond"></div></div>
        </div>
        <div class="testimonials-grid">
            @php
            $testimonials = [
                ['text'=>'قطعة الأثاث التي اقتنيتها من قطعة وقصة أضافت لمنزلي روحاً لا مثيل لها. جودة استثنائية وخدمة راقية.','name'=>'فهد العتيبي','loc'=>'الرياض','init'=>'ف'],
                ['text'=>'الباحث الشخصي وجد لي قطعة ساعة نادرة من القرن التاسع عشر كنت أبحث عنها سنوات. شكراً جزيلاً.','name'=>'نورة الشمري','loc'=>'جدة','init'=>'ن'],
                ['text'=>'مزاد رائع واحترافي. حصلت على لوحة أصيلة بسعر منطقي. سأعود بالتأكيد للمزاد القادم.','name'=>'خالد المنصور','loc'=>'الدمام','init'=>'خ'],
            ];
            @endphp
            @foreach($testimonials as $t)
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        @for($i=0;$i<5;$i++) <i class="fa-solid fa-star"></i> @endfor
                    </div>
                    <p class="testimonial-text">{{ $t['text'] }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">{{ $t['init'] }}</div>
                        <div>
                            <div class="testimonial-name">{{ $t['name'] }}</div>
                            <div class="testimonial-loc">{{ $t['loc'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     8. Trust Badges
     ══════════════════════════════════════════════════════════════ --}}
<section class="trust-badges">
    <div class="container">
        <div class="trust-grid">
            @php
            $badges = [
                ['icon'=>'fa-shield-halved', 'name'=>'ضمان الأصالة',   'desc'=>'كل قطعة موثّقة ومعتمدة من خبرائنا'],
                ['icon'=>'fa-truck-fast',    'name'=>'شحن آمن',         'desc'=>'تغليف متخصص يحمي قطعتك حتى بابك'],
                ['icon'=>'fa-rotate-left',   'name'=>'إرجاع مضمون',     'desc'=>'14 يوم للإرجاع بدون أسئلة'],
                ['icon'=>'fa-headset',       'name'=>'دعم على مدار الساعة','desc'=>'فريقنا جاهز للإجابة على استفساراتك'],
                ['icon'=>'fa-lock',          'name'=>'دفع آمن',          'desc'=>'حماية كاملة لبياناتك المصرفية'],
            ];
            @endphp
            @foreach($badges as $b)
                <div class="trust-item">
                    <div class="trust-icon"><i class="fa-solid {{ $b['icon'] }}"></i></div>
                    <div class="trust-name">{{ $b['name'] }}</div>
                    <div class="trust-desc">{{ $b['desc'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleWishlist(productId, btn) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const icon = btn.querySelector('i');
        if (data.added) {
            icon.className = 'fa-solid fa-heart';
            icon.style.color = '#DC2626';
            btn.classList.add('active');
        } else {
            icon.className = 'fa-regular fa-heart';
            icon.style.color = '';
            btn.classList.remove('active');
        }
    });
}
</script>
@endpush
