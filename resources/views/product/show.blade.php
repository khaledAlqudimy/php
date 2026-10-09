@extends('layouts.app')
@section('title', $product->title)
@section('meta_description', Str::limit(strip_tags($product->short_desc ?: $product->description ?: $product->story ?: $product->title . ' — قطعة أنتيك أصيلة من قطعة وقصة'), 160))
@section('og_type', 'product')
@if($product->images->isNotEmpty())
    @section('og_image', url(Storage::url($product->images->first()->url)))
@endif

@push('head')
{{-- بيانات منظمة لمحركات البحث (تظهر السعر والتوفر في نتائج Google) --}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product->title,
    'sku'         => $product->sku,
    'description' => Str::limit(strip_tags($product->short_desc ?: $product->description ?: ''), 500) ?: null,
    'image'       => $product->images->map(fn ($img) => url(Storage::url($img->url)))->values()->all() ?: null,
    'category'    => $product->category?->name,
    'material'    => $product->material,
    'countryOfOrigin' => $product->country_of_origin,
    'offers'      => [
        '@type'         => 'Offer',
        'url'           => route('product.show', $product->slug),
        'priceCurrency' => 'SAR',
        'price'         => number_format((float) $product->price, 2, '.', ''),
        'availability'  => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut',
        'itemCondition' => 'https://schema.org/UsedCondition',
    ],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
@endpush

@push('styles')
<style>
/* ══ Product Page ══ */
.product-page { padding: 2.5rem 0 5rem; }

/* ── Breadcrumb ── */
.breadcrumb {
    display: flex; align-items: center; gap: .5rem;
    font-size: .8rem; color: var(--color-text-muted);
    margin-bottom: 2rem; flex-wrap: wrap;
}
.breadcrumb a { color: var(--color-text-muted); }
.breadcrumb a:hover { color: var(--color-gold); }
.breadcrumb span { color: var(--color-border); }
.breadcrumb .current { color: var(--color-brown); font-weight: 600; }

/* ── Product Layout ── */
.product-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}

/* ══ Gallery ══ */
.gallery { position: sticky; top: 88px; }

.gallery-main {
    aspect-ratio: 4/3;
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--color-bg-2);
    border: 1px solid var(--color-border);
    margin-bottom: .75rem;
    position: relative;
}

.gallery-main img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s ease;
    cursor: zoom-in;
}

.gallery-main:hover img { transform: scale(1.03); }

.gallery-placeholder {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 1rem;
    color: var(--color-text-muted);
}

.gallery-thumbs {
    display: flex; gap: .5rem; overflow-x: auto;
    padding-bottom: .25rem;
}

.gallery-thumb {
    flex-shrink: 0;
    width: 72px; height: 72px;
    border-radius: var(--radius);
    overflow: hidden;
    border: 2px solid var(--color-border);
    cursor: pointer;
    transition: var(--transition);
}

.gallery-thumb:hover,
.gallery-thumb.active { border-color: var(--color-gold); }

.gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

.rare-ribbon {
    position: absolute; top: 1rem; right: 1rem;
    background: linear-gradient(135deg, #7C3AED, #5B21B6);
    color: #fff; font-size: .75rem; font-weight: 700;
    padding: .3rem .85rem; border-radius: 100px;
    display: flex; align-items: center; gap: .35rem;
}

/* ══ Product Info ══ */
.product-category {
    font-size: .75rem; font-weight: 700;
    color: var(--color-gold); text-transform: uppercase;
    letter-spacing: 1px; margin-bottom: .5rem;
    display: flex; align-items: center; gap: .4rem;
}

.product-title {
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 700;
    color: var(--color-brown);
    line-height: 1.3;
    margin-bottom: 1.25rem;
}

/* Price Block */
.price-block {
    display: flex; align-items: center; gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1.25rem;
    background: var(--color-bg);
    border-radius: var(--radius);
    border: 1px solid var(--color-border);
}

.price-main { font-size: 2rem; font-weight: 700; color: var(--color-brown); }
.price-was  { font-size: 1rem; text-decoration: line-through; color: var(--color-text-muted); }
.price-discount {
    background: #DC2626; color: #fff;
    font-size: .75rem; font-weight: 700;
    padding: .2rem .6rem; border-radius: 100px;
}

/* Meta Tags */
.product-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem;
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex; align-items: flex-start; gap: .6rem;
    padding: .7rem .85rem;
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: var(--radius);
    font-size: .8rem;
}

.meta-icon { color: var(--color-gold); font-size: .9rem; margin-top: .05rem; flex-shrink: 0; }
.meta-label { color: var(--color-text-muted); font-size: .7rem; display: block; margin-bottom: .1rem; }
.meta-value { color: var(--color-brown); font-weight: 600; }

/* Stock Badge */
.stock-status {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .85rem; font-weight: 600;
    padding: .4rem .9rem;
    border-radius: 100px;
    margin-bottom: 1.5rem;
}
.stock-in-page  { background: #DCFCE7; color: #166534; }
.stock-out-page { background: #FEE2E2; color: #991B1B; }

/* Action Buttons */
.product-actions { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 2rem; }

.btn-add-cart {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    padding: 1rem;
    background: var(--color-brown);
    color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    text-decoration: none;
}
.btn-add-cart:hover:not(:disabled) {
    background: var(--color-gold); color: var(--color-brown);
    transform: translateY(-2px); box-shadow: var(--shadow-md);
}
.btn-add-cart:disabled { opacity: .5; cursor: not-allowed; }
.btn-add-cart.in-cart  { background: #166534; color: #fff; }

.btn-wishlist-page {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    padding: .875rem;
    background: transparent;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .9rem; font-weight: 600;
    color: var(--color-text-muted);
    cursor: pointer; transition: var(--transition);
}
.btn-wishlist-page:hover,
.btn-wishlist-page.active {
    border-color: #DC2626; color: #DC2626; background: #FEF2F2;
}
.btn-wishlist-page.active i { font-weight: 900; }

/* ── Short Description ── */
.product-short-desc {
    color: var(--color-text-muted);
    font-size: .9rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

/* Share */
.product-share {
    display: flex; align-items: center; gap: .75rem;
    font-size: .8rem; color: var(--color-text-muted);
    padding-top: 1rem;
    border-top: 1px solid var(--color-border);
}
.share-btn {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: var(--color-bg);
    border: 1px solid var(--color-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--color-text-muted); font-size: .85rem;
    transition: var(--transition);
}
.share-btn:hover { border-color: var(--color-gold); color: var(--color-gold); }

/* ══ Tabs Section ══ */
.product-tabs { margin-top: 4rem; }

.tabs-nav {
    display: flex; gap: 0;
    border-bottom: 2px solid var(--color-border);
    margin-bottom: 2rem; overflow-x: auto;
}

.tab-btn {
    padding: .875rem 1.5rem;
    font-family: var(--font-main); font-size: .9rem; font-weight: 600;
    color: var(--color-text-muted);
    background: none; border: none; cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: var(--transition); white-space: nowrap;
}
.tab-btn.active { color: var(--color-gold); border-bottom-color: var(--color-gold); }
.tab-btn:hover  { color: var(--color-brown); }

.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Story Tab */
.story-content {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    border: 1px solid var(--color-border);
    position: relative;
}

.story-quote-icon {
    position: absolute; top: 1.5rem; right: 2rem;
    font-size: 5rem; color: var(--color-gold); opacity: .08;
    font-family: Georgia, serif; line-height: 1;
}

.story-text {
    font-size: 1rem; line-height: 2;
    color: var(--color-text);
}

/* Details Tab */
.details-table { width: 100%; border-collapse: collapse; }
.details-table tr { border-bottom: 1px solid var(--color-border); }
.details-table tr:last-child { border: none; }
.details-table td {
    padding: .875rem 1rem;
    font-size: .9rem;
}
.details-table td:first-child {
    color: var(--color-text-muted); font-weight: 600;
    width: 160px; background: var(--color-bg);
}
.details-table td:last-child { color: var(--color-text); }

/* ══ Related Products ══ */
.related-section { margin-top: 5rem; }
.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
}

/* Responsive */
@media (max-width: 900px) {
    .product-grid { grid-template-columns: 1fr; gap: 2rem; }
    .gallery { position: static; }
    .product-meta { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 480px) {
    .product-meta { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="product-page">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('shop') }}">المتجر</a>
            @if($product->category)
                <span>/</span>
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}">
                    {{ $product->category->name }}
                </a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->title }}</span>
        </nav>

        {{-- ══ Main Product Grid ══ --}}
        <div class="product-grid">

            {{-- ─── Gallery ─── --}}
            <div class="gallery">
                <div class="gallery-main" id="mainImage">
                    @if($product->images->isNotEmpty())
                        <img
                            src="{{ Storage::url($product->images->first()->url) }}"
                            alt="{{ $product->title }}"
                            id="mainImg"
                        >
                    @else
                        <div class="gallery-placeholder">
                            <i class="fa-solid fa-image fa-3x" style="opacity:.2;"></i>
                            <span style="color:var(--color-text-muted);font-size:.875rem;">لا توجد صور</span>
                        </div>
                    @endif

                    @if($product->is_rare)
                        <div class="rare-ribbon">
                            <i class="fa-solid fa-gem fa-xs"></i> قطعة نادرة
                        </div>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                    <div class="gallery-thumbs">
                        @foreach($product->images as $i => $image)
                            <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                                 onclick="switchImage('{{ Storage::url($image->url) }}', this)">
                                <img src="{{ Storage::url($image->url) }}" alt="{{ $product->title }} {{ $i+1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ─── Product Info ─── --}}
            <div class="product-info">

                {{-- Category --}}
                @if($product->category)
                    <div class="product-category">
                        <i class="fa-solid fa-tag fa-xs"></i>
                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}">
                            {{ $product->category->name }}
                        </a>
                    </div>
                @endif

                <h1 class="product-title">{{ $product->title }}</h1>

                @if($product->short_desc)
                    <p class="product-short-desc">{{ $product->short_desc }}</p>
                @endif

                {{-- Price --}}
                <div class="price-block">
                    <div class="price-main">{{ number_format($product->price, 0) }} ر.س</div>
                    @if($product->hasDiscount())
                        <div class="price-was">{{ number_format($product->compare_at_price, 0) }} ر.س</div>
                        <div class="price-discount">خصم {{ $product->discountPercent() }}%</div>
                    @endif
                </div>

                {{-- Meta ──────────────────────────── --}}
                @php
                    $metas = [
                        ['icon'=>'fa-clock',          'label'=>'الحقبة',         'value'=>$product->era],
                        ['icon'=>'fa-location-dot',   'label'=>'البلد الأصلي',   'value'=>$product->country_of_origin],
                        ['icon'=>'fa-cube',            'label'=>'المادة',         'value'=>$product->material],
                        ['icon'=>'fa-ruler-combined', 'label'=>'الأبعاد',        'value'=>$product->dimensions],
                        ['icon'=>'fa-star-half-stroke','label'=>'الحالة',        'value'=>$product->condition],
                        ['icon'=>'fa-barcode',         'label'=>'رمز القطعة',    'value'=>$product->sku],
                    ];
                    $metas = array_filter($metas, fn($m) => !empty($m['value']));
                @endphp
                @if(!empty($metas))
                    <div class="product-meta">
                        @foreach($metas as $meta)
                            <div class="meta-item">
                                <i class="fa-solid {{ $meta['icon'] }} meta-icon"></i>
                                <div>
                                    <span class="meta-label">{{ $meta['label'] }}</span>
                                    <span class="meta-value">{{ $meta['value'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Stock --}}
                <div>
                    @if($product->stock > 0)
                        <span class="stock-status stock-in-page">
                            <i class="fa-solid fa-circle-check fa-sm"></i> متاحة للشراء
                        </span>
                    @else
                        <span class="stock-status stock-out-page">
                            <i class="fa-solid fa-circle-xmark fa-sm"></i> نفدت هذه القطعة
                        </span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="product-actions">
                    @auth
                        @if($product->stock > 0)
                            <button
                                id="addToCartBtn"
                                class="btn-add-cart {{ $inCart ? 'in-cart' : '' }}"
                                onclick="addToCart({{ $product->id }})"
                            >
                                @if($inCart)
                                    <i class="fa-solid fa-check fa-sm"></i> تمت الإضافة للسلة
                                @else
                                    <i class="fa-solid fa-bag-shopping fa-sm"></i> أضف إلى السلة
                                @endif
                            </button>
                        @else
                            <button class="btn-add-cart" disabled>
                                <i class="fa-solid fa-ban fa-sm"></i> غير متاحة
                            </button>
                        @endif
                        <button
                            id="wishlistBtn"
                            class="btn-wishlist-page {{ $inWishlist ? 'active' : '' }}"
                            onclick="toggleWishlist({{ $product->id }})"
                        >
                            <i class="{{ $inWishlist ? 'fa-solid' : 'fa-regular' }} fa-heart fa-sm"></i>
                            {{ $inWishlist ? 'في المفضلة' : 'أضف للمفضلة' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn-add-cart">
                            <i class="fa-solid fa-right-to-bracket fa-sm"></i> سجّل دخولك للشراء
                        </a>
                    @endauth
                </div>

                {{-- Share --}}
                <div class="product-share">
                    <span>مشاركة:</span>
                    <a href="https://wa.me/?text={{ urlencode($product->title . ' - ' . request()->url()) }}"
                       target="_blank" class="share-btn" title="واتساب">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->title) }}&url={{ urlencode(request()->url()) }}"
                       target="_blank" class="share-btn" title="تويتر">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <button class="share-btn" onclick="copyLink()" title="نسخ الرابط">
                        <i class="fa-solid fa-link"></i>
                    </button>
                    <span id="copiedMsg" style="color:var(--color-gold);font-size:.75rem;display:none;">تم النسخ!</span>
                </div>

            </div>
        </div>

        {{-- ══ Tabs ══ --}}
        <div class="product-tabs">
            <div class="tabs-nav">
                @if($product->story)
                    <button class="tab-btn active" onclick="switchTab('story', this)">
                        <i class="fa-solid fa-scroll fa-sm"></i> القصة
                    </button>
                @endif
                <button class="tab-btn {{ !$product->story ? 'active' : '' }}" onclick="switchTab('details', this)">
                    <i class="fa-solid fa-list fa-sm"></i> التفاصيل
                </button>
                <button class="tab-btn" onclick="switchTab('shipping', this)">
                    <i class="fa-solid fa-truck-fast fa-sm"></i> الشحن والتوصيل
                </button>
            </div>

            {{-- القصة --}}
            @if($product->story)
                <div class="tab-panel active" id="tab-story">
                    <div class="story-content">
                        <div class="story-quote-icon">"</div>
                        <p class="story-text">{{ $product->story }}</p>
                    </div>
                </div>
            @endif

            {{-- التفاصيل --}}
            <div class="tab-panel {{ !$product->story ? 'active' : '' }}" id="tab-details">
                <table class="details-table">
                    @foreach([
                        ['الاسم',          $product->title],
                        ['رمز القطعة',     $product->sku],
                        ['التصنيف',        $product->category?->name],
                        ['الحقبة',         $product->era],
                        ['البلد الأصلي',   $product->country_of_origin],
                        ['المادة',         $product->material],
                        ['الأبعاد',        $product->dimensions],
                        ['الحالة',         $product->condition],
                        ['المشاهدات',      number_format($product->views_count) . ' مشاهدة'],
                    ] as [$label, $value])
                        @if($value)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>

            {{-- الشحن --}}
            <div class="tab-panel" id="tab-shipping">
                <div style="background:var(--color-white);border-radius:var(--radius-lg);padding:2rem;border:1px solid var(--color-border);">
                    @php
                        $shippingFee  = \App\Models\Setting::get('shipping_fee', '30');
                        $freeMin      = \App\Models\Setting::get('free_shipping_min', '500');
                        $minDays      = \App\Models\Setting::get('delivery_days_min', '3');
                        $maxDays      = \App\Models\Setting::get('delivery_days_max', '7');
                    @endphp
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;">
                        @foreach([
                            ['fa-truck-fast',   'التوصيل',  "خلال {$minDays}–{$maxDays} أيام عمل"],
                            ['fa-box',          'التغليف',  'تغليف متخصص لحماية قطعتك'],
                            ['fa-coins',        'رسوم الشحن', "{$shippingFee} ر.س (مجاني فوق {$freeMin} ر.س)"],
                            ['fa-rotate-left',  'الإرجاع',  '14 يوم من تاريخ الاستلام'],
                        ] as [$icon, $title, $desc])
                            <div style="display:flex;gap:.75rem;padding:1rem;background:var(--color-bg);border-radius:var(--radius);border:1px solid var(--color-border);">
                                <i class="fa-solid {{ $icon }}" style="color:var(--color-gold);font-size:1.25rem;margin-top:.1rem;flex-shrink:0;"></i>
                                <div>
                                    <div style="font-weight:700;color:var(--color-brown);font-size:.875rem;margin-bottom:.2rem;">{{ $title }}</div>
                                    <div style="font-size:.8rem;color:var(--color-text-muted);">{{ $desc }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ Related Products ══ --}}
        @if($related->isNotEmpty())
            <div class="related-section">
                <div class="section-header" style="text-align:right;margin-bottom:1.5rem;">
                    <span class="section-label">من نفس التصنيف</span>
                    <h2 class="section-title" style="font-size:1.4rem;">قطع مشابهة</h2>
                </div>
                <div class="related-grid">
                    @foreach($related as $rel)
                        <x-product-card :product="$rel" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Gallery Switch ───
function switchImage(src, thumb) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// ─── Tabs ───
function switchTab(id, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}

// ─── Add to Cart ───
function addToCart(productId) {
    const btn = document.getElementById('addToCartBtn');
    btn.disabled = true;

    fetch('/cart', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fa-solid fa-check fa-sm"></i> تمت الإضافة للسلة';
            btn.classList.add('in-cart');
        }
        btn.disabled = false;
    })
    .catch(() => { btn.disabled = false; });
}

// ─── Wishlist Toggle ───
function toggleWishlist(productId) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        const btn = document.getElementById('wishlistBtn');
        const icon = btn.querySelector('i');
        if (data.added) {
            btn.classList.add('active');
            icon.className = 'fa-solid fa-heart fa-sm';
            btn.childNodes[1].textContent = ' في المفضلة';
        } else {
            btn.classList.remove('active');
            icon.className = 'fa-regular fa-heart fa-sm';
            btn.childNodes[1].textContent = ' أضف للمفضلة';
        }
    });
}

// ─── Copy Link ───
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const msg = document.getElementById('copiedMsg');
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 2000);
    });
}
</script>
@endpush
