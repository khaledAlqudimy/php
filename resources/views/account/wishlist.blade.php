@extends('layouts.app')
@section('title', 'المفضلة')

@push('styles')
<style>
.account-page { padding: 2.5rem 0 5rem; }

.account-header {
    background: var(--color-brown);
    padding: 3rem 0 2.5rem;
}
.account-header-title { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); }

/* Account Nav */
.account-nav {
    display: flex; gap: .25rem;
    overflow-x: auto;
    margin: 2rem 0 2.5rem;
    padding-bottom: .25rem;
}

.account-nav-link {
    display: flex; align-items: center; gap: .4rem;
    padding: .6rem 1.1rem;
    border-radius: var(--radius);
    font-size: .875rem; font-weight: 600;
    color: var(--color-text-muted);
    white-space: nowrap;
    transition: var(--transition);
    text-decoration: none;
}

.account-nav-link:hover { color: var(--color-brown); background: var(--color-bg-2); }
.account-nav-link.active { background: var(--color-brown); color: var(--color-gold); }

/* Wishlist Grid */
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}

/* Empty */
.wishlist-empty {
    text-align: center; padding: 5rem 2rem;
    color: var(--color-text-muted);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
}
.wishlist-empty i { font-size: 3.5rem; opacity: .15; margin-bottom: 1.25rem; }
.wishlist-empty h3 { font-size: 1.1rem; color: var(--color-brown); margin-bottom: .5rem; }

/* Override product card remove button for wishlist */
.wishlist-remove-btn {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    width: 100%; padding: .5rem;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    font-size: .8rem; font-weight: 600;
    color: #DC2626; cursor: pointer;
    transition: var(--transition);
}
.wishlist-remove-btn:hover { background: #DC2626; color: #fff; }

.wishlist-card {
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--color-border);
    background: var(--color-white);
    display: flex; flex-direction: column;
    transition: var(--transition);
}
.wishlist-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }
</style>
@endpush

@section('content')

<div class="account-header">
    <div class="container">
        <h1 class="account-header-title">حسابي</h1>
    </div>
</div>

<div class="account-page">
    <div class="container">

        {{-- ─── Account Navigation ─── --}}
        <nav class="account-nav">
            <a href="{{ route('account.profile') }}"  class="account-nav-link {{ request()->routeIs('account.profile') ? 'active' : '' }}">
                <i class="fa-regular fa-circle-user fa-sm"></i> الملف الشخصي
            </a>
            <a href="{{ route('account.orders') }}"   class="account-nav-link {{ request()->routeIs('account.orders') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt fa-sm"></i> طلباتي
            </a>
            <a href="{{ route('account.wishlist') }}" class="account-nav-link {{ request()->routeIs('account.wishlist') ? 'active' : '' }}">
                <i class="fa-regular fa-heart fa-sm"></i> المفضلة
                @if($items->isNotEmpty())
                    <span style="background:var(--color-gold);color:var(--color-brown);border-radius:100px;padding:.1rem .45rem;font-size:.7rem;font-weight:700;">{{ $items->count() }}</span>
                @endif
            </a>
            <a href="{{ route('account.addresses') }}" class="account-nav-link {{ request()->routeIs('account.addresses') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot fa-sm"></i> عناوين التوصيل
            </a>
        </nav>

        {{-- ─── Content ─── --}}
        <div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 style="font-size:1.2rem;font-weight:700;color:var(--color-brown);">قائمة المفضلة</h2>
                <p style="font-size:.85rem;color:var(--color-text-muted);margin-top:.2rem;">
                    {{ $items->count() }} قطعة محفوظة
                </p>
            </div>
            @if($items->isNotEmpty())
                <a href="{{ route('shop') }}" class="btn btn-outline" style="font-size:.875rem;">
                    <i class="fa-solid fa-plus fa-sm"></i> إضافة المزيد
                </a>
            @endif
        </div>

        @if($items->isEmpty())
            <div class="wishlist-empty">
                <i class="fa-regular fa-heart"></i>
                <h3>قائمة المفضلة فارغة</h3>
                <p>احفظ القطع التي تعجبك لتجدها هنا بسهولة</p>
                <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top:1rem;">
                    <i class="fa-solid fa-arrow-left fa-sm"></i> تصفح المتجر
                </a>
            </div>
        @else
            <div class="wishlist-grid" id="wishlistGrid">
                @foreach($items as $item)
                    <div class="wishlist-card" id="wishlist-card-{{ $item->product->id }}">
                        {{-- بطاقة المنتج --}}
                        <a href="{{ route('product.show', $item->product->slug) }}" style="display:flex;flex-direction:column;flex:1;text-decoration:none;color:inherit;">
                            {{-- صورة --}}
                            <div style="aspect-ratio:4/3;overflow:hidden;background:var(--color-bg-2);position:relative;">
                                @if($item->product->mainImage())
                                    <img
                                        src="{{ Storage::url($item->product->mainImage()) }}"
                                        alt="{{ $item->product->title }}"
                                        style="width:100%;height:100%;object-fit:cover;transition:transform .4s;"
                                        onmouseover="this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                    >
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:2rem;opacity:.2;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                @if($item->product->is_rare)
                                    <div style="position:absolute;top:.6rem;right:.6rem;background:#7C3AED;color:#fff;font-size:.65rem;font-weight:700;padding:.2rem .6rem;border-radius:100px;">
                                        <i class="fa-solid fa-gem fa-xs"></i> نادرة
                                    </div>
                                @endif
                            </div>
                            {{-- معلومات --}}
                            <div style="padding:.9rem;flex:1;">
                                @if($item->product->category)
                                    <div style="font-size:.7rem;font-weight:700;color:var(--color-gold);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem;">{{ $item->product->category->name }}</div>
                                @endif
                                <div style="font-size:.9rem;font-weight:700;color:var(--color-brown);line-height:1.4;margin-bottom:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $item->product->title }}</div>
                                <div style="font-size:1rem;font-weight:700;color:var(--color-brown);">
                                    {{ number_format($item->product->price, 0) }} ر.س
                                    @if($item->product->hasDiscount())
                                        <span style="font-size:.8rem;text-decoration:line-through;color:var(--color-text-muted);font-weight:400;margin-right:.3rem;">{{ number_format($item->product->compare_at_price, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>

                        {{-- أزرار --}}
                        <div style="padding:.75rem;display:flex;gap:.5rem;border-top:1px solid var(--color-border);">
                            @if($item->product->stock > 0)
                                <button
                                    onclick="addToCartFromWishlist({{ $item->product->id }}, this)"
                                    style="flex:1;padding:.55rem;background:var(--color-brown);color:var(--color-gold);border:none;border-radius:var(--radius);font-family:var(--font-main);font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:.35rem;">
                                    <i class="fa-solid fa-bag-shopping fa-xs"></i> للسلة
                                </button>
                            @else
                                <span style="flex:1;text-align:center;padding:.55rem;background:var(--color-bg-2);border-radius:var(--radius);font-size:.8rem;color:var(--color-text-muted);">نفدت</span>
                            @endif
                            <button
                                onclick="removeFromWishlist({{ $item->product->id }})"
                                style="width:36px;height:36px;border:1px solid #FECACA;border-radius:var(--radius);background:#FEF2F2;color:#DC2626;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;"
                                title="إزالة">
                                <i class="fa-solid fa-trash-can fa-xs"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function addToCartFromWishlist(productId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i>';

    fetch('/cart', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fa-solid fa-check fa-xs"></i> تمت الإضافة';
            btn.style.background = '#166534';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-bag-shopping fa-xs"></i> للسلة';
            btn.disabled = false;
        }
    });
}

function removeFromWishlist(productId) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(() => {
        const card = document.getElementById('wishlist-card-' + productId);
        card.style.opacity = '0';
        card.style.transform = 'scale(.95)';
        card.style.transition = 'all .3s';
        setTimeout(() => card.remove(), 300);
    });
}
</script>
@endpush
