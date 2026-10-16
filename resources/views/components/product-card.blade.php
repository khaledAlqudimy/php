{{--
    مكوّن بطاقة المنتج — يُستخدم في كل أقسام الموقع
    الاستخدام:
        <x-product-card :product="$product" />
        <x-product-card :product="$product" size="large" />
--}}
@props(['product', 'size' => 'default'])

@php
    $image   = $product->mainImage();
    $isLarge = $size === 'large';
@endphp

<a href="{{ route('product.show', $product->slug) }}"
   class="product-card {{ $isLarge ? 'product-card--large' : '' }}">

    {{-- ─── صورة المنتج ─── --}}
    <div class="product-card__image-wrap">

        {{-- شارات المنتج --}}
        <div class="product-card__badges">
            @if($product->is_rare)
                <span class="pc-badge pc-badge--rare">
                    <i class="fa-solid fa-gem fa-xs"></i> نادرة
                </span>
            @endif
            @if($product->is_featured)
                <span class="pc-badge pc-badge--featured">مميز</span>
            @endif
            @if($product->hasDiscount())
                <span class="pc-badge pc-badge--sale">-{{ $product->discountPercent() }}%</span>
            @endif
        </div>

        {{-- زر المفضلة --}}
        @auth
        <button class="wishlist-btn"
            onclick="event.preventDefault(); toggleWishlist({{ $product->id }}, this)"
            title="أضف للمفضلة">
            <i class="fa-regular fa-heart"></i>
        </button>
        @endauth

        {{-- الصورة أو البديل --}}
        @if($image)
            <img
                src="{{ Storage::url($image) }}"
                alt="{{ $product->title }}"
                class="product-card__img"
                loading="lazy"
            >
        @else
            <div class="product-card__placeholder">
                <i class="fa-solid fa-image" style="font-size:2rem;opacity:.3;"></i>
                <span>قطعة وقصة</span>
            </div>
        @endif

        {{-- Overlay عند الـ Hover --}}
        <div class="product-card__overlay">
            <span class="overlay-btn">
                <i class="fa-solid fa-eye fa-sm"></i> عرض التفاصيل
            </span>
        </div>
    </div>

    {{-- ─── محتوى البطاقة ─── --}}
    <div class="product-card__body">
        @if($product->category)
            <span class="product-card__category">{{ $product->category->name }}</span>
        @endif

        <h3 class="product-card__title">{{ $product->title }}</h3>

        @if($product->era || $product->country_of_origin)
            <p class="product-card__meta">
                @if($product->era)
                    <span><i class="fa-regular fa-clock fa-xs"></i> {{ $product->era }}</span>
                @endif
                @if($product->country_of_origin)
                    <span><i class="fa-solid fa-location-dot fa-xs"></i> {{ $product->country_of_origin }}</span>
                @endif
            </p>
        @endif

        <div class="product-card__footer">
            <div class="product-card__price">
                <span class="price-current">{{ number_format($product->price, 0) }} ر.س</span>
                @if($product->hasDiscount())
                    <span class="price-old">{{ number_format($product->compare_at_price, 0) }}</span>
                @endif
            </div>
            @if($product->stock > 0)
                <span class="stock-badge stock-in">متاح</span>
            @else
                <span class="stock-badge stock-out">نفد</span>
            @endif
        </div>
    </div>
</a>

{{-- تنسيق البطاقة مع المكوّن نفسه — يعمل في أي صفحة (المتجر، المنتجات المشابهة، الرئيسية) --}}
@once
@push('styles')
<style>
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

.pc-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 100px;
    font-size: .7rem; font-weight: 700;
}

.pc-badge--rare    { background: #7C3AED; color: #fff; }
.pc-badge--featured { background: var(--color-gold); color: var(--color-brown); }
.pc-badge--sale    { background: #DC2626; color: #fff; }

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
</style>
@endpush
@endonce
