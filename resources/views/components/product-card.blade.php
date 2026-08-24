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
                <span class="badge badge--rare">
                    <i class="fa-solid fa-gem fa-xs"></i> نادرة
                </span>
            @endif
            @if($product->is_featured)
                <span class="badge badge--featured">مميز</span>
            @endif
            @if($product->hasDiscount())
                <span class="badge badge--sale">-{{ $product->discountPercent() }}%</span>
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
