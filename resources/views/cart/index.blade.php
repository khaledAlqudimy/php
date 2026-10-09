@extends('layouts.app')
@section('title', 'سلة التسوق')
@section('noindex', true)

@push('styles')
<style>
/* ══ Cart Page ══ */
.cart-page { padding: 2.5rem 0 5rem; }

.cart-header {
    background: var(--color-brown);
    padding: 3rem 0 2.5rem;
}
.cart-header-inner {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
}
.cart-header-title { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); }
.cart-count-label {
    color: rgba(255,255,255,.6); font-size: .9rem;
    display: flex; align-items: center; gap: .4rem;
}

/* ── Layout ── */
.cart-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2rem;
    align-items: start;
    margin-top: 2.5rem;
}

/* ══ Cart Items ══ */
.cart-items-wrap {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
}

.cart-items-header {
    display: grid;
    grid-template-columns: 1fr 120px 120px 48px;
    gap: 1rem;
    padding: .875rem 1.5rem;
    background: var(--color-bg);
    border-bottom: 1px solid var(--color-border);
    font-size: .75rem; font-weight: 700;
    color: var(--color-text-muted); text-transform: uppercase; letter-spacing: .5px;
}

.cart-item {
    display: grid;
    grid-template-columns: 1fr 120px 120px 48px;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--color-border);
    align-items: center;
    transition: background .2s;
}
.cart-item:last-child { border-bottom: none; }
.cart-item:hover { background: var(--color-bg); }

/* Product Info */
.cart-item-info { display: flex; gap: 1rem; align-items: center; }

.cart-item-img {
    width: 80px; height: 80px;
    border-radius: var(--radius);
    overflow: hidden;
    border: 1px solid var(--color-border);
    flex-shrink: 0;
    background: var(--color-bg-2);
}
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
.cart-item-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-text-muted); font-size: 1.5rem; opacity: .3;
}

.cart-item-name {
    font-size: .9rem; font-weight: 700;
    color: var(--color-brown); line-height: 1.4;
    margin-bottom: .2rem;
}
.cart-item-name a { color: inherit; }
.cart-item-name a:hover { color: var(--color-gold); }
.cart-item-cat { font-size: .75rem; color: var(--color-text-muted); }

/* Quantity */
.qty-control {
    display: flex; align-items: center; gap: .4rem;
}
.qty-btn {
    width: 30px; height: 30px;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    background: var(--color-white);
    font-size: .9rem; font-weight: 700;
    color: var(--color-brown);
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
}
.qty-btn:hover:not(:disabled) {
    border-color: var(--color-gold);
    color: var(--color-gold);
}
.qty-btn:disabled { opacity: .35; cursor: not-allowed; }

.qty-input {
    width: 38px; height: 30px;
    text-align: center;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .875rem; font-weight: 700;
    color: var(--color-brown);
    background: var(--color-bg);
}
.qty-input:focus { outline: none; border-color: var(--color-gold); }

/* Item Total */
.cart-item-total {
    font-size: .95rem; font-weight: 700; color: var(--color-brown); text-align: center;
}

/* Delete */
.cart-item-del {
    width: 34px; height: 34px;
    border: 1.5px solid transparent;
    border-radius: var(--radius);
    background: none;
    color: var(--color-text-muted);
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}
.cart-item-del:hover {
    border-color: #DC2626; color: #DC2626; background: #FEF2F2;
}

/* Loading overlay */
.item-loading { opacity: .5; pointer-events: none; }

/* ══ Empty Cart ══ */
.cart-empty {
    text-align: center; padding: 5rem 2rem;
    color: var(--color-text-muted);
}
.cart-empty i { font-size: 4rem; opacity: .2; margin-bottom: 1.25rem; }
.cart-empty h3 { font-size: 1.2rem; color: var(--color-brown); margin-bottom: .5rem; }
.cart-empty p { font-size: .9rem; margin-bottom: 1.5rem; }

/* ══ Summary Sidebar ══ */
.cart-summary {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
    position: sticky;
    top: 88px;
}

.summary-header {
    padding: 1.25rem 1.5rem;
    background: var(--color-brown);
    font-weight: 700; font-size: 1rem;
    color: var(--color-gold);
    display: flex; align-items: center; gap: .5rem;
}

.summary-body { padding: 1.5rem; }

.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .9rem; padding: .6rem 0;
    border-bottom: 1px solid var(--color-border);
    color: var(--color-text-muted);
}
.summary-row:last-of-type { border: none; }
.summary-row span:last-child { font-weight: 600; color: var(--color-text); }

.summary-row.free span:last-child { color: #166534; font-weight: 700; }

.summary-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 1.1rem 0 .5rem;
    margin-top: .5rem;
    border-top: 2px solid var(--color-brown);
    font-size: 1.1rem; font-weight: 700;
    color: var(--color-brown);
}

.summary-total .amount { font-size: 1.3rem; color: var(--color-gold); }

/* Free shipping progress */
.free-shipping-bar { margin: 1rem 0; }
.free-shipping-label { font-size: .78rem; color: var(--color-text-muted); margin-bottom: .4rem; }
.free-shipping-label strong { color: var(--color-gold); }
.progress-track {
    height: 6px; background: var(--color-bg-2);
    border-radius: 100px; overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--color-gold), var(--color-gold-2));
    border-radius: 100px;
    transition: width .4s ease;
}

/* Coupon */
.coupon-form {
    display: flex; gap: .5rem;
    margin: 1rem 0;
}
.coupon-input {
    flex: 1;
    padding: .65rem .9rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .875rem;
    background: var(--color-bg);
    direction: ltr; text-align: center;
    text-transform: uppercase;
}
.coupon-input:focus { outline: none; border-color: var(--color-gold); background: #fff; }
.coupon-btn {
    padding: .65rem 1rem;
    background: var(--color-brown);
    color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: .85rem; font-weight: 700;
    cursor: pointer; transition: var(--transition); white-space: nowrap;
}
.coupon-btn:hover { background: var(--color-gold); color: var(--color-brown); }
.coupon-msg { font-size: .78rem; margin-top: .35rem; }
.coupon-ok  { color: #166534; }
.coupon-err { color: #DC2626; }

.btn-checkout {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    width: 100%; padding: 1rem;
    background: var(--color-gold);
    color: var(--color-brown);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    margin-top: 1rem; text-decoration: none;
}
.btn-checkout:hover {
    background: var(--color-gold-2);
    transform: translateY(-2px); box-shadow: var(--shadow-md);
}

.btn-continue {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%;
    padding: .75rem;
    background: transparent;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .875rem;
    color: var(--color-text-muted);
    cursor: pointer; transition: var(--transition);
    margin-top: .5rem; text-decoration: none;
}
.btn-continue:hover { border-color: var(--color-gold); color: var(--color-gold); }

/* Responsive */
@media (max-width: 900px) {
    .cart-grid { grid-template-columns: 1fr; }
    .cart-summary { position: static; }
    .cart-items-header { display: none; }
    .cart-item { grid-template-columns: 1fr auto; grid-template-rows: auto auto; gap: .75rem; }
    .cart-item-total { grid-column: 2; grid-row: 1; align-self: start; }
    .cart-item-del   { grid-column: 2; grid-row: 2; align-self: end; }
    .qty-control     { grid-column: 1; grid-row: 2; }
}
</style>
@endpush

@section('content')

<div class="cart-header">
    <div class="container cart-header-inner">
        <h1 class="cart-header-title">سلة التسوق</h1>
        <span class="cart-count-label">
            <i class="fa-solid fa-bag-shopping"></i>
            <span id="itemCount">{{ $items->count() }}</span> قطعة
        </span>
    </div>
</div>

<div class="cart-page">
    <div class="container">

        @if($items->isEmpty())
        {{-- ══ سلة فارغة ══ --}}
        <div class="cart-items-wrap">
            <div class="cart-empty">
                <i class="fa-solid fa-bag-shopping"></i>
                <h3>سلتك فارغة</h3>
                <p>لم تضف أي قطع بعد. تصفّح متجرنا واكتشف قطعاً رائعة!</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left fa-sm"></i> تصفح المتجر
                </a>
            </div>
        </div>

        @else
        {{-- ══ السلة بها منتجات ══ --}}
        <div class="cart-grid">

            {{-- ─── Items ─── --}}
            <div>
                <div class="cart-items-wrap">
                    <div class="cart-items-header">
                        <span>المنتج</span>
                        <span style="text-align:center">الكمية</span>
                        <span style="text-align:center">المجموع</span>
                        <span></span>
                    </div>
                    <div id="cartItemsList">
                        @foreach($items as $item)
                        <div class="cart-item" id="item-{{ $item->id }}" data-id="{{ $item->id }}">

                            {{-- معلومات المنتج --}}
                            <div class="cart-item-info">
                                <div class="cart-item-img">
                                    @if($item->product->mainImage())
                                        <img src="{{ Storage::url($item->product->mainImage()) }}" alt="{{ $item->product->title }}">
                                    @else
                                        <div class="cart-item-img-placeholder">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="cart-item-name">
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            {{ $item->product->title }}
                                        </a>
                                    </div>
                                    @if($item->product->category)
                                        <div class="cart-item-cat">{{ $item->product->category->name }}</div>
                                    @endif
                                    <div style="font-size:.8rem;color:var(--color-gold);font-weight:600;margin-top:.25rem;">
                                        {{ number_format($item->product->price, 0) }} ر.س / قطعة
                                    </div>
                                </div>
                            </div>

                            {{-- الكمية --}}
                            <div class="qty-control">
                                <button class="qty-btn"
                                    onclick="changeQty({{ $item->id }}, -1)"
                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                                <input
                                    class="qty-input"
                                    type="number"
                                    id="qty-{{ $item->id }}"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    max="{{ $item->product->stock }}"
                                    onchange="updateQty({{ $item->id }}, this.value)"
                                >
                                <button class="qty-btn"
                                    onclick="changeQty({{ $item->id }}, 1)"
                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                            </div>

                            {{-- المجموع الفرعي --}}
                            <div class="cart-item-total" id="subtotal-{{ $item->id }}">
                                {{ number_format($item->product->price * $item->quantity, 0) }} ر.س
                            </div>

                            {{-- حذف --}}
                            <button class="cart-item-del" onclick="removeItem({{ $item->id }})" title="حذف">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ─── Summary ─── --}}
            <div>
                <div class="cart-summary">
                    <div class="summary-header">
                        <i class="fa-solid fa-receipt fa-sm"></i> ملخص الطلب
                    </div>
                    <div class="summary-body">

                        {{-- شريط الشحن المجاني --}}
                        @php $progress = min(($subtotal / $freeMin) * 100, 100); @endphp
                        @if($subtotal < $freeMin)
                            <div class="free-shipping-bar">
                                <div class="free-shipping-label">
                                    أضف <strong>{{ number_format($freeMin - $subtotal, 0) }} ر.س</strong> للحصول على شحن مجاني
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill" id="shippingProgress" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @else
                            <div style="background:#DCFCE7;color:#166534;border-radius:var(--radius);padding:.6rem .9rem;font-size:.8rem;font-weight:600;margin-bottom:1rem;display:flex;gap:.4rem;align-items:center;">
                                <i class="fa-solid fa-truck-fast fa-sm"></i> تهانينا! الشحن مجاني لطلبك
                            </div>
                        @endif

                        {{-- كوبون --}}
                        <div class="coupon-form">
                            <input
                                type="text" id="couponCode"
                                class="coupon-input"
                                placeholder="كود الخصم"
                                maxlength="30"
                            >
                            <button class="coupon-btn" onclick="applyCoupon()">تطبيق</button>
                        </div>
                        <div id="couponMsg" class="coupon-msg"></div>

                        {{-- الإجماليات --}}
                        <div style="margin-top:1rem;">
                            <div class="summary-row">
                                <span>المجموع الفرعي</span>
                                <span id="summarySubtotal">{{ number_format($subtotal, 0) }} ر.س</span>
                            </div>
                            <div class="summary-row {{ $shippingFee == 0 ? 'free' : '' }}">
                                <span>الشحن</span>
                                <span id="summaryShipping">{{ $shippingFee == 0 ? 'مجاني' : number_format($shippingFee, 0) . ' ر.س' }}</span>
                            </div>
                            @if($vatEnabled)
                                <div class="summary-row">
                                    <span>ضريبة القيمة المضافة ({{ $vatPercent }}%)</span>
                                    <span id="summaryTax">{{ number_format($taxAmount, 0) }} ر.س</span>
                                </div>
                            @endif
                            <div id="discountRow" class="summary-row" style="display:none;color:#166534;">
                                <span>الخصم</span>
                                <span id="summaryDiscount" style="color:#166534;">-0 ر.س</span>
                            </div>
                        </div>

                        <div class="summary-total">
                            <span>الإجمالي</span>
                            <span class="amount" id="summaryTotal">{{ number_format($total, 0) }} ر.س</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn-checkout">
                            <i class="fa-solid fa-credit-card fa-sm"></i> إتمام الطلب
                        </a>
                        <a href="{{ route('shop') }}" class="btn-continue">
                            <i class="fa-solid fa-arrow-right fa-sm"></i> مواصلة التسوق
                        </a>

                        <div style="display:flex;align-items:center;justify-content:center;gap:.75rem;margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--color-border);">
                            @foreach(['fa-lock','fa-shield-halved','fa-truck-fast'] as $icon)
                                <i class="fa-solid {{ $icon }}" style="color:var(--color-text-muted);font-size:.9rem;" title="دفع آمن"></i>
                            @endforeach
                            <span style="font-size:.75rem;color:var(--color-text-muted);">دفع آمن ومضمون</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── تغيير الكمية بالأزرار ───
function changeQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    const newVal = parseInt(input.value) + delta;
    if (newVal < 1 || newVal > parseInt(input.max)) return;
    input.value = newVal;
    updateQty(id, newVal);
}

// ─── تحديث الكمية ───
function updateQty(id, qty) {
    const row = document.getElementById('item-' + id);
    row.classList.add('item-loading');

    fetch(`/cart/${id}`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ quantity: parseInt(qty) })
    })
    .then(r => r.json())
    .then(data => {
        row.classList.remove('item-loading');
        if (!data.success) return;

        document.getElementById('subtotal-' + id).textContent = data.item_subtotal.split('.')[0] + ' ر.س';
        updateSummaryUI(data);

        // تحديث أزرار +/-
        const input = document.getElementById('qty-' + id);
        row.querySelectorAll('.qty-btn')[0].disabled = parseInt(input.value) <= 1;
        row.querySelectorAll('.qty-btn')[1].disabled = parseInt(input.value) >= parseInt(input.max);
    });
}

// ─── حذف منتج ───
function removeItem(id) {
    if (!confirm('هل تريد حذف هذه القطعة من السلة؟')) return;

    const row = document.getElementById('item-' + id);
    row.classList.add('item-loading');

    fetch(`/cart/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        row.remove();
        document.getElementById('itemCount').textContent = data.cart_count;

        // تحديث عداد السلة في Header
        const badge = document.querySelector('.badge');
        if (badge) badge.textContent = data.cart_count;

        if (data.empty) {
            location.reload();
        } else {
            updateSummaryUI(data);
        }
    });
}

// ─── تحديث الـ Summary ───
function updateSummaryUI(data) {
    if (data.subtotal) document.getElementById('summarySubtotal').textContent = data.subtotal.split('.')[0] + ' ر.س';
    if (data.total)    document.getElementById('summaryTotal').textContent    = data.total.split('.')[0] + ' ر.س';
    if (data.tax)      document.getElementById('summaryTax') && (document.getElementById('summaryTax').textContent = data.tax.split('.')[0] + ' ر.س');
}

// ─── تطبيق كوبون ───
function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    const msg  = document.getElementById('couponMsg');
    if (!code) { msg.className = 'coupon-msg coupon-err'; msg.textContent = 'أدخل كود الخصم'; return; }

    fetch('/coupon/validate', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valid) {
            msg.className = 'coupon-msg coupon-ok';
            msg.textContent = `✅ ${data.message} — خصم ${data.discount} ر.س`;
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('summaryDiscount').textContent = '-' + data.discount + ' ر.س';
            document.getElementById('summaryTotal').textContent = data.new_total + ' ر.س';
        } else {
            msg.className = 'coupon-msg coupon-err';
            msg.textContent = '❌ ' + data.message;
        }
    });
}
</script>
@endpush
