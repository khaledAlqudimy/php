@extends('layouts.app')
@section('title', 'إتمام الطلب')
@section('noindex', true)

@push('styles')
<style>
.checkout-header { background: var(--color-brown); padding: 3rem 0 2.5rem; }
.checkout-title { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); }

.checkout-page { padding: 2.5rem 0 5rem; }

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    align-items: start;
}

/* ── Steps ── */
.checkout-steps {
    display: flex; gap: 0;
    margin-bottom: 2.5rem;
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
}

.step {
    flex: 1; display: flex; align-items: center; gap: .6rem;
    padding: 1rem 1.25rem;
    font-size: .8rem; font-weight: 600;
    color: var(--color-text-muted);
    border-left: 1px solid var(--color-border);
    position: relative;
}
.step:first-child { border: none; }
.step.active { color: var(--color-gold); background: rgba(182,138,74,.05); }
.step.done   { color: #166534; }

.step-num {
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--color-bg-2); border: 2px solid var(--color-border);
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; flex-shrink: 0;
}
.step.active .step-num { background: var(--color-gold); color: var(--color-brown); border-color: var(--color-gold); }
.step.done   .step-num { background: #166534; color: #fff; border-color: #166534; }

/* ── Section Cards ── */
.checkout-section {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.section-head {
    padding: 1.1rem 1.5rem;
    background: var(--color-bg);
    border-bottom: 1px solid var(--color-border);
    display: flex; align-items: center; gap: .6rem;
    font-size: .9rem; font-weight: 700; color: var(--color-brown);
}
.section-head i { color: var(--color-gold); }

.section-body { padding: 1.5rem; }

/* ── Address Cards ── */
.address-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1rem;
}

.address-card {
    border: 2px solid var(--color-border);
    border-radius: var(--radius);
    padding: 1rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}
.address-card:hover { border-color: var(--color-gold); }
.address-card.selected {
    border-color: var(--color-gold);
    background: rgba(182,138,74,.05);
}
.address-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

.address-check {
    position: absolute; top: .75rem; left: .75rem;
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--color-border);
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; color: transparent;
    transition: var(--transition);
}
.address-card.selected .address-check {
    border-color: var(--color-gold);
    background: var(--color-gold);
    color: var(--color-brown);
}

.address-name { font-weight: 700; color: var(--color-brown); margin-bottom: .25rem; font-size: .9rem; }
.address-detail { font-size: .8rem; color: var(--color-text-muted); line-height: 1.7; }
.address-default {
    display: inline-block; font-size: .65rem; font-weight: 700;
    background: var(--color-gold); color: var(--color-brown);
    padding: .1rem .45rem; border-radius: 100px; margin-bottom: .4rem;
}

.btn-add-address {
    display: flex; align-items: center; gap: .5rem;
    padding: .875rem 1rem;
    border: 2px dashed var(--color-border);
    border-radius: var(--radius);
    background: transparent;
    font-family: var(--font-main); font-size: .875rem;
    color: var(--color-text-muted); cursor: pointer;
    transition: var(--transition); width: 100%; justify-content: center;
    margin-top: 1rem;
}
.btn-add-address:hover { border-color: var(--color-gold); color: var(--color-gold); }

/* ── Payment Methods ── */
.payment-options { display: flex; flex-direction: column; gap: .75rem; }

.payment-option {
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem 1.25rem;
    border: 2px solid var(--color-border);
    border-radius: var(--radius);
    cursor: pointer; transition: var(--transition);
    position: relative;
}
.payment-option:hover { border-color: var(--color-gold); }
.payment-option.selected { border-color: var(--color-gold); background: rgba(182,138,74,.04); }
.payment-option input { position: absolute; opacity: 0; }

.payment-icon {
    width: 42px; height: 42px; border-radius: var(--radius);
    background: var(--color-bg-2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}

.payment-label { font-weight: 700; font-size: .9rem; color: var(--color-brown); }
.payment-desc  { font-size: .78rem; color: var(--color-text-muted); margin-top: .1rem; }

.payment-check {
    margin-right: auto;
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--color-border);
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; color: transparent; flex-shrink: 0;
    transition: var(--transition);
}
.payment-option.selected .payment-check {
    border-color: var(--color-gold); background: var(--color-gold); color: var(--color-brown);
}

/* ── Notes ── */
.notes-input {
    width: 100%; padding: .75rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .875rem;
    color: var(--color-text); resize: vertical; min-height: 80px;
    background: var(--color-bg); direction: rtl;
}
.notes-input:focus { outline: none; border-color: var(--color-gold); background: #fff; }

/* ── Summary Sidebar ── */
.order-summary {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    position: sticky; top: 88px;
    overflow: hidden;
}

.summary-head {
    padding: 1.1rem 1.5rem;
    background: var(--color-brown);
    font-weight: 700; color: var(--color-gold);
    display: flex; align-items: center; gap: .5rem;
    font-size: .9rem;
}

.summary-items { max-height: 260px; overflow-y: auto; padding: 1rem 1.5rem; }

.summary-item {
    display: flex; gap: .75rem; align-items: center;
    padding: .6rem 0; border-bottom: 1px solid var(--color-border);
    font-size: .85rem;
}
.summary-item:last-child { border: none; }

.summary-item-img {
    width: 48px; height: 48px; border-radius: var(--radius);
    overflow: hidden; flex-shrink: 0; background: var(--color-bg-2);
    border: 1px solid var(--color-border);
}
.summary-item-img img { width: 100%; height: 100%; object-fit: cover; }

.summary-item-name { font-weight: 600; color: var(--color-brown); line-height: 1.3; }
.summary-item-qty  { font-size: .75rem; color: var(--color-text-muted); }
.summary-item-price { margin-right: auto; font-weight: 700; color: var(--color-brown); white-space: nowrap; }

/* Coupon */
.coupon-row { display: flex; gap: .5rem; padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); }
.coupon-inp {
    flex: 1; padding: .65rem .85rem;
    border: 1.5px solid var(--color-border); border-radius: var(--radius);
    font-family: var(--font-main); font-size: .85rem;
    direction: ltr; text-align: center; text-transform: uppercase;
    background: var(--color-bg);
}
.coupon-inp:focus { outline: none; border-color: var(--color-gold); background: #fff; }

.coupon-apply {
    padding: .65rem 1rem;
    background: var(--color-brown); color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: .85rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
}
.coupon-apply:hover { background: var(--color-gold); color: var(--color-brown); }

/* Totals */
.summary-totals { padding: 1rem 1.5rem; border-top: 1px solid var(--color-border); }

.total-row {
    display: flex; justify-content: space-between;
    font-size: .875rem; color: var(--color-text-muted);
    padding: .4rem 0;
}
.total-row span:last-child { font-weight: 600; color: var(--color-text); }
.total-row.free span:last-child { color: #166534; }
.total-row.discount span { color: #166534; }
.total-row.grand {
    border-top: 2px solid var(--color-brown);
    margin-top: .5rem; padding-top: .875rem;
    font-size: 1.05rem; font-weight: 700; color: var(--color-brown);
}
.total-row.grand span:last-child { color: var(--color-gold); font-size: 1.2rem; }

.btn-confirm-order {
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    width: 100%; padding: 1.1rem;
    background: var(--color-gold); color: var(--color-brown);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: var(--transition); margin-top: 1rem;
}
.btn-confirm-order:hover {
    background: var(--color-gold-2);
    transform: translateY(-2px); box-shadow: var(--shadow-md);
}

.secure-badges {
    display: flex; align-items: center; justify-content: center; gap: .75rem;
    padding: .875rem 0 0;
    font-size: .75rem; color: var(--color-text-muted);
}

/* Add Address Modal */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
    z-index: 200; display: flex; align-items: center; justify-content: center;
    padding: 1rem; opacity: 0; visibility: hidden; transition: var(--transition);
}
.modal-overlay.open { opacity: 1; visibility: visible; }

.modal-box {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    width: 100%; max-width: 520px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,.25);
    transform: translateY(20px); transition: var(--transition);
}
.modal-overlay.open .modal-box { transform: translateY(0); }

.modal-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--color-border);
    font-weight: 700; color: var(--color-brown);
}
.modal-close {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--color-bg); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-text-muted); font-size: 1rem;
    transition: var(--transition);
}
.modal-close:hover { background: #FEE2E2; color: #DC2626; }

.modal-body { padding: 1.5rem; }

.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-group-m { margin-bottom: 1rem; }
.form-label-m { display: block; font-size: .8rem; font-weight: 700; color: var(--color-brown); margin-bottom: .35rem; }
.form-input-m {
    width: 100%; padding: .7rem .9rem;
    border: 1.5px solid var(--color-border); border-radius: var(--radius);
    font-family: var(--font-main); font-size: .875rem;
    color: var(--color-text); background: var(--color-bg); direction: rtl;
    transition: var(--transition);
}
.form-input-m:focus { outline: none; border-color: var(--color-gold); background: #fff; }

@media (max-width: 900px) {
    .checkout-grid { grid-template-columns: 1fr; }
    .order-summary { position: static; }
    .form-row-2 { grid-template-columns: 1fr; }
    .checkout-steps { display: none; }
}
</style>
@endpush

@section('content')

<div class="checkout-header">
    <div class="container">
        <h1 class="checkout-title">إتمام الطلب</h1>
    </div>
</div>

<div class="checkout-page">
    <div class="container">

        {{-- Steps --}}
        <div class="checkout-steps">
            <div class="step done">
                <div class="step-num"><i class="fa-solid fa-check fa-xs"></i></div>
                <span>السلة</span>
            </div>
            <div class="step active">
                <div class="step-num">2</div>
                <span>التفاصيل</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <span>التأكيد</span>
            </div>
        </div>

        <form action="{{ route('order.place') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="coupon_code" id="appliedCoupon">

            <div class="checkout-grid">

                {{-- ══ Right Column ══ --}}
                <div>

                    {{-- ── عنوان التوصيل ── --}}
                    <div class="checkout-section">
                        <div class="section-head">
                            <i class="fa-solid fa-location-dot"></i>
                            عنوان التوصيل
                        </div>
                        <div class="section-body">
                            @if($addresses->isEmpty())
                                <div style="text-align:center;padding:2rem;color:var(--color-text-muted);">
                                    <i class="fa-solid fa-map-marker-alt fa-2x" style="opacity:.2;margin-bottom:.75rem;"></i>
                                    <p style="font-size:.875rem;">لا يوجد عناوين محفوظة</p>
                                </div>
                            @else
                                <div class="address-grid">
                                    @foreach($addresses as $address)
                                        <label class="address-card {{ $address->is_default || $loop->first ? 'selected' : '' }}">
                                            <input
                                                type="radio"
                                                name="address_id"
                                                value="{{ $address->id }}"
                                                {{ $address->is_default || $loop->first ? 'checked' : '' }}
                                                onchange="selectCard(this, '.address-card')"
                                            >
                                            <div class="address-check">
                                                <i class="fa-solid fa-check fa-xs"></i>
                                            </div>
                                            @if($address->is_default)
                                                <div class="address-default">افتراضي</div>
                                            @endif
                                            <div class="address-name">{{ $address->full_name }}</div>
                                            <div class="address-detail">
                                                {{ $address->phone }}<br>
                                                {{ $address->city }}، {{ $address->country }}
                                                @if($address->street), {{ $address->street }}@endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            @error('address_id')
                                <div style="color:#DC2626;font-size:.8rem;margin-top:.5rem;">{{ $message }}</div>
                            @enderror

                            <button type="button" class="btn-add-address" onclick="openModal()">
                                <i class="fa-solid fa-plus fa-sm"></i> إضافة عنوان جديد
                            </button>
                        </div>
                    </div>

                    {{-- ── طريقة الدفع ── --}}
                    <div class="checkout-section">
                        <div class="section-head">
                            <i class="fa-solid fa-credit-card"></i>
                            طريقة الدفع
                        </div>
                        <div class="section-body">
                            <div class="payment-options">

                                <label class="payment-option selected">
                                    <input type="radio" name="payment_method" value="cod" checked onchange="selectCard(this, '.payment-option')">
                                    <div class="payment-icon">💵</div>
                                    <div>
                                        <div class="payment-label">الدفع عند الاستلام</div>
                                        <div class="payment-desc">ادفع نقداً عند وصول طلبك</div>
                                    </div>
                                    <div class="payment-check"><i class="fa-solid fa-check fa-xs"></i></div>
                                </label>

                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="bank_transfer" onchange="selectCard(this, '.payment-option')">
                                    <div class="payment-icon">🏦</div>
                                    <div>
                                        <div class="payment-label">تحويل بنكي</div>
                                        <div class="payment-desc">سيتم إرسال تفاصيل الحساب البنكي عبر البريد</div>
                                    </div>
                                    <div class="payment-check"><i class="fa-solid fa-check fa-xs"></i></div>
                                </label>

                            </div>
                            @error('payment_method')
                                <div style="color:#DC2626;font-size:.8rem;margin-top:.5rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ── ملاحظات ── --}}
                    <div class="checkout-section">
                        <div class="section-head">
                            <i class="fa-solid fa-note-sticky"></i>
                            ملاحظات (اختياري)
                        </div>
                        <div class="section-body">
                            <textarea
                                name="notes"
                                class="notes-input"
                                placeholder="تعليمات التوصيل، أو أي ملاحظات خاصة بطلبك..."
                            >{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- ══ Left Column: Summary ══ --}}
                <div>
                    <div class="order-summary">
                        <div class="summary-head">
                            <i class="fa-solid fa-bag-shopping fa-sm"></i>
                            ملخص الطلب ({{ $cartItems->count() }} قطعة)
                        </div>

                        {{-- المنتجات --}}
                        <div class="summary-items">
                            @foreach($cartItems as $item)
                                <div class="summary-item">
                                    <div class="summary-item-img">
                                        @if($item->product->mainImage())
                                            <img src="{{ Storage::url($item->product->mainImage()) }}" alt="{{ $item->product->title }}">
                                        @endif
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="summary-item-name">{{ Str::limit($item->product->title, 35) }}</div>
                                        <div class="summary-item-qty">الكمية: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="summary-item-price">{{ number_format($item->product->price * $item->quantity, 0) }} ر.س</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- كوبون الخصم --}}
                        <div class="coupon-row">
                            <input type="text" id="couponInput" class="coupon-inp" placeholder="كود الخصم">
                            <button type="button" class="coupon-apply" onclick="applyCouponCheckout()">تطبيق</button>
                        </div>
                        <div id="couponResult" style="padding:0 1.5rem .75rem;font-size:.78rem;"></div>

                        {{-- الإجماليات --}}
                        <div class="summary-totals">
                            <div class="total-row">
                                <span>المجموع الفرعي</span>
                                <span>{{ number_format($subtotal, 0) }} ر.س</span>
                            </div>
                            <div class="total-row {{ $shippingFee == 0 ? 'free' : '' }}">
                                <span>الشحن</span>
                                <span>{{ $shippingFee == 0 ? 'مجاني 🎉' : number_format($shippingFee, 0) . ' ر.س' }}</span>
                            </div>
                            @if($vatEnabled)
                                <div class="total-row">
                                    <span>ضريبة القيمة المضافة ({{ $vatPercent }}%)</span>
                                    <span>{{ number_format($taxAmount, 0) }} ر.س</span>
                                </div>
                            @endif
                            <div class="total-row discount" id="discountRow" style="{{ session('coupon_discount') ? '' : 'display:none' }}">
                                <span>الخصم</span>
                                <span id="discountAmount">-0 ر.س</span>
                            </div>
                            <div class="total-row grand">
                                <span>الإجمالي</span>
                                <span id="grandTotal">{{ number_format($total, 0) }} ر.س</span>
                            </div>

                            <button type="submit" class="btn-confirm-order">
                                <i class="fa-solid fa-check-circle fa-sm"></i> تأكيد الطلب
                            </button>

                            <div class="secure-badges">
                                <i class="fa-solid fa-lock" style="color:var(--color-text-muted);"></i>
                                <span>طلبك آمن ومحمي</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ══ Modal: إضافة عنوان ══ --}}
<div class="modal-overlay" id="addressModal" onclick="closeModalOutside(event)">
    <div class="modal-box">
        <div class="modal-head">
            <span>إضافة عنوان جديد</span>
            <button class="modal-close" onclick="closeModal()" type="button">✕</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="form-row-2">
                    <div class="form-group-m">
                        <label class="form-label-m">الاسم الكامل <span style="color:#DC2626">*</span></label>
                        <input type="text" name="full_name" class="form-input-m" placeholder="محمد عبدالله" required>
                    </div>
                    <div class="form-group-m">
                        <label class="form-label-m">رقم الهاتف <span style="color:#DC2626">*</span></label>
                        <input type="tel" name="phone" class="form-input-m" placeholder="+966 5X XXX XXXX" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group-m">
                        <label class="form-label-m">الدولة <span style="color:#DC2626">*</span></label>
                        <input type="text" name="country" class="form-input-m" value="المملكة العربية السعودية" required>
                    </div>
                    <div class="form-group-m">
                        <label class="form-label-m">المدينة <span style="color:#DC2626">*</span></label>
                        <input type="text" name="city" class="form-input-m" placeholder="الرياض" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div class="form-group-m">
                        <label class="form-label-m">الحي</label>
                        <input type="text" name="district" class="form-input-m" placeholder="حي النزهة">
                    </div>
                    <div class="form-group-m">
                        <label class="form-label-m">الرمز البريدي</label>
                        <input type="text" name="postal" class="form-input-m" placeholder="12345">
                    </div>
                </div>
                <div class="form-group-m">
                    <label class="form-label-m">الشارع والرقم</label>
                    <input type="text" name="street" class="form-input-m" placeholder="شارع الملك فهد، مبنى 12">
                </div>
                <label class="filter-check" style="margin-bottom:1.25rem;">
                    <input type="checkbox" name="is_default" value="1">
                    تعيين كعنوان افتراضي
                </label>
                <button type="submit" class="btn-confirm-order" style="margin-top:0;">
                    <i class="fa-solid fa-plus fa-sm"></i> إضافة العنوان
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── تحديد بطاقة ───
function selectCard(radio, selector) {
    document.querySelectorAll(selector).forEach(c => c.classList.remove('selected'));
    radio.closest(selector).classList.add('selected');
}

// ─── كوبون ───
function applyCouponCheckout() {
    const code   = document.getElementById('couponInput').value.trim();
    const result = document.getElementById('couponResult');
    if (!code) { result.innerHTML = '<span style="color:#DC2626">أدخل كود الخصم</span>'; return; }

    fetch('/coupon/validate', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.valid) {
            result.innerHTML = `<span style="color:#166534">✅ ${data.message} — خصم ${data.discount} ر.س</span>`;
            document.getElementById('appliedCoupon').value = code;
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountAmount').textContent = '-' + data.discount + ' ر.س';
            document.getElementById('grandTotal').textContent = data.new_total + ' ر.س';
        } else {
            result.innerHTML = `<span style="color:#DC2626">❌ ${data.message}</span>`;
            document.getElementById('appliedCoupon').value = '';
        }
    });
}

// ─── Modal ───
function openModal()  { document.getElementById('addressModal').classList.add('open'); }
function closeModal() { document.getElementById('addressModal').classList.remove('open'); }
function closeModalOutside(e) { if (e.target.id === 'addressModal') closeModal(); }
</script>
@endpush
