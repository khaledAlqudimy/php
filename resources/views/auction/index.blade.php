@extends('layouts.app')
@section('title', 'المزادات')
@section('meta_description', 'مزادات قطعة وقصة الحصرية على القطع النادرة — سجّل اهتمامك وحدد ميزانيتك قبل موعد المزاد.')

@push('styles')
<style>
/* ══ Auction Page ══ */
.auction-hero {
    background: linear-gradient(135deg, #1A0F08 0%, var(--color-brown) 100%);
    padding: 5rem 0 4rem;
    position: relative; overflow: hidden;
    text-align: center;
}
.auction-hero::before {
    content: '';
    position: absolute; top: -100px; right: -100px;
    width: 400px; height: 400px;
    border: 70px solid rgba(182,138,74,.06);
    border-radius: 50%;
}
.auction-hero::after {
    content: '';
    position: absolute; bottom: -80px; left: -80px;
    width: 300px; height: 300px;
    border: 50px solid rgba(182,138,74,.05);
    border-radius: 50%;
}
.auction-hero-inner { position: relative; z-index: 1; }
.auction-hero-badge {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(182,138,74,.15);
    border: 1px solid rgba(182,138,74,.3);
    color: var(--color-gold);
    font-size: .8rem; font-weight: 700;
    padding: .4rem 1.25rem; border-radius: 100px;
    margin-bottom: 1.5rem; letter-spacing: .5px;
}
.auction-hero h1 {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700; color: #fff; margin-bottom: 1rem; line-height: 1.2;
}
.auction-hero h1 span { color: var(--color-gold); }
.auction-hero p {
    color: rgba(255,255,255,.65);
    font-size: 1rem; max-width: 540px;
    margin: 0 auto 2rem; line-height: 1.8;
}

/* How it works */
.how-it-works {
    display: flex; gap: 2rem;
    justify-content: center; flex-wrap: wrap;
    margin-top: 2.5rem;
}
.how-step {
    display: flex; align-items: center; gap: .75rem;
    color: rgba(255,255,255,.7); font-size: .85rem;
}
.how-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(182,138,74,.2); border: 1px solid rgba(182,138,74,.4);
    color: var(--color-gold); font-weight: 700; font-size: .8rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ══ Auctions Grid ══ */
.auction-page { padding: 4rem 0 5rem; }

.auctions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.75rem;
}

/* ── Auction Card ── */
.auction-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
    transition: var(--transition);
    display: flex; flex-direction: column;
}
.auction-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(58,36,24,.14);
    border-color: var(--color-gold);
}

.auction-card-img {
    aspect-ratio: 16/9;
    position: relative;
    overflow: hidden;
    background: var(--color-bg-2);
}
.auction-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s ease;
}
.auction-card:hover .auction-card-img img { transform: scale(1.05); }

.auction-card-img-placeholder {
    width: 100%; height: 100%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: .5rem;
    color: var(--color-text-muted); font-size: .875rem; opacity: .5;
}

/* Countdown badge on image */
.countdown-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(26,15,8,.95), transparent);
    padding: 1.5rem 1rem .9rem;
    display: flex; align-items: flex-end; gap: .5rem;
}

.countdown-timer {
    display: flex; gap: .4rem; align-items: center;
}

.countdown-block {
    background: rgba(182,138,74,.9);
    color: var(--color-brown);
    border-radius: 6px;
    padding: .3rem .5rem;
    text-align: center; min-width: 42px;
}
.countdown-num { font-size: 1.1rem; font-weight: 700; line-height: 1; display: block; }
.countdown-lbl { font-size: .55rem; font-weight: 600; display: block; opacity: .8; }
.countdown-sep { color: var(--color-gold); font-weight: 700; font-size: 1.1rem; margin-top: -.3rem; }

.auction-live-badge {
    position: absolute; top: .75rem; right: .75rem;
    background: #DC2626;
    color: #fff; font-size: .7rem; font-weight: 700;
    padding: .25rem .75rem; border-radius: 100px;
    display: flex; align-items: center; gap: .35rem;
    animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .7; } }

/* Card Body */
.auction-card-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }

.auction-card-cat {
    font-size: .7rem; font-weight: 700; color: var(--color-gold);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: .4rem;
}

.auction-card-title {
    font-size: 1rem; font-weight: 700;
    color: var(--color-brown); line-height: 1.4;
    margin-bottom: .75rem;
}

.auction-card-meta {
    display: flex; justify-content: space-between;
    font-size: .8rem; color: var(--color-text-muted);
    margin-bottom: 1rem; padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
}

.auction-price-label { font-size: .7rem; color: var(--color-text-muted); }
.auction-price { font-size: 1.1rem; font-weight: 700; color: var(--color-brown); }

.auction-interest-count {
    display: flex; align-items: center; gap: .3rem;
    font-size: .78rem; color: var(--color-text-muted);
}

.btn-register-interest {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%; padding: .875rem;
    background: var(--color-brown); color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: .9rem; font-weight: 700;
    cursor: pointer; transition: var(--transition); margin-top: auto;
}
.btn-register-interest:hover {
    background: var(--color-gold); color: var(--color-brown);
    transform: translateY(-1px); box-shadow: var(--shadow-md);
}
.btn-closed {
    background: var(--color-bg-2);
    color: var(--color-text-muted);
    cursor: not-allowed;
}
.btn-closed:hover { transform: none; box-shadow: none; background: var(--color-bg-2); color: var(--color-text-muted); }

/* ══ Closed Auctions ══ */
.closed-section { margin-top: 4rem; }
.closed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.25rem;
    opacity: .7;
}
.closed-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
}
.closed-card-img { aspect-ratio: 4/3; overflow: hidden; background: var(--color-bg-2); }
.closed-card-img img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(30%); }
.closed-card-body { padding: 1rem; }
.closed-card-title { font-size: .875rem; font-weight: 700; color: var(--color-brown); margin-bottom: .3rem; }
.closed-card-meta { font-size: .75rem; color: var(--color-text-muted); }
.closed-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    background: #F3F4F6; color: #6B7280;
    font-size: .7rem; font-weight: 700;
    padding: .2rem .6rem; border-radius: 100px;
    margin-bottom: .5rem;
}

/* ══ Interest Modal ══ */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
    z-index: 200; display: flex; align-items: center; justify-content: center;
    padding: 1rem; opacity: 0; visibility: hidden; transition: var(--transition);
}
.modal-overlay.open { opacity: 1; visibility: visible; }
.modal-box {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    width: 100%; max-width: 520px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,.3);
    transform: translateY(20px); transition: var(--transition);
    border-top: 4px solid var(--color-gold);
}
.modal-overlay.open .modal-box { transform: translateY(0); }

.modal-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--color-border);
}
.modal-title { font-size: 1rem; font-weight: 700; color: var(--color-brown); }
.modal-close {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--color-bg); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-text-muted); font-size: 1rem; transition: var(--transition);
}
.modal-close:hover { background: #FEE2E2; color: #DC2626; }

.modal-product-preview {
    display: flex; gap: 1rem; align-items: center;
    padding: 1rem 1.5rem;
    background: var(--color-bg);
    border-bottom: 1px solid var(--color-border);
}
.modal-product-img {
    width: 56px; height: 56px; border-radius: var(--radius);
    overflow: hidden; flex-shrink: 0; border: 1px solid var(--color-border);
    background: var(--color-bg-2);
}
.modal-product-img img { width: 100%; height: 100%; object-fit: cover; }
.modal-product-name { font-weight: 700; font-size: .9rem; color: var(--color-brown); }
.modal-product-price { font-size: .8rem; color: var(--color-gold); font-weight: 600; margin-top: .15rem; }

.modal-body { padding: 1.5rem; }
.form-group-m { margin-bottom: 1.1rem; }
.form-label-m {
    display: block; font-size: .8rem; font-weight: 700;
    color: var(--color-brown); margin-bottom: .35rem;
}
.form-input-m {
    width: 100%; padding: .7rem .9rem;
    border: 1.5px solid var(--color-border); border-radius: var(--radius);
    font-family: var(--font-main); font-size: .875rem;
    color: var(--color-text); background: var(--color-bg); direction: rtl;
    transition: var(--transition);
}
.form-input-m:focus { outline: none; border-color: var(--color-gold); background: #fff; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

.btn-submit-modal {
    width: 100%; padding: .9rem;
    background: var(--color-brown); color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: .95rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
}
.btn-submit-modal:hover { background: var(--color-gold); color: var(--color-brown); }
</style>
@endpush

@section('content')

{{-- ══ Hero ══ --}}
<div class="auction-hero">
    <div class="container auction-hero-inner">
        <div class="auction-hero-badge">
            <i class="fa-solid fa-gavel fa-xs"></i> مزادات حصرية
        </div>
        <h1>فرصتك للحصول على<br><span>قطعة لا تُقدر بثمن</span></h1>
        <p>سجّل اهتمامك بالمزادات القادمة وسنتواصل معك بكل تفاصيل المزاد قبل موعده بوقت كافٍ.</p>

        <div class="how-it-works">
            @foreach(['تصفح القطع المتاحة', 'سجّل اهتمامك', 'انتظر تواصلنا', 'احصل على قطعتك'] as $i => $step)
                <div class="how-step">
                    <div class="how-num">{{ $i + 1 }}</div>
                    <span>{{ $step }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══ Active Auctions ══ --}}
<div class="auction-page">
    <div class="container">

        {{-- Flash success --}}
        @if(session('success'))
            <div style="background:#DCFCE7;color:#166534;border:1px solid #BBF7D0;border-radius:var(--radius);padding:1rem 1.25rem;margin-bottom:2rem;display:flex;align-items:center;gap:.6rem;font-weight:600;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="section-header" style="text-align:right;margin-bottom:2rem;">
            <span class="section-label">مفتوحة الآن</span>
            <h2 class="section-title">المزادات النشطة</h2>
        </div>

        @if($activeAuctions->isEmpty())
            <div style="text-align:center;padding:4rem;background:var(--color-white);border-radius:var(--radius-lg);border:1px solid var(--color-border);color:var(--color-text-muted);">
                <i class="fa-solid fa-gavel fa-3x" style="opacity:.15;margin-bottom:1rem;"></i>
                <h3 style="color:var(--color-brown);margin-bottom:.5rem;">لا توجد مزادات نشطة حالياً</h3>
                <p>تابعنا وسجّل بريدك لتكون أول من يعلم بالمزاد القادم.</p>
            </div>
        @else
            <div class="auctions-grid">
                @foreach($activeAuctions as $auction)
                    <div class="auction-card">
                        <div class="auction-card-img">
                            @if($auction->product->images->isNotEmpty())
                                <img src="{{ Storage::url($auction->product->images->first()->url) }}"
                                     alt="{{ $auction->product->title }}">
                            @else
                                <div class="auction-card-img-placeholder">
                                    <i class="fa-solid fa-gavel fa-2x"></i>
                                    <span>قطعة للمزاد</span>
                                </div>
                            @endif

                            <div class="auction-live-badge">
                                <i class="fa-solid fa-circle fa-xs"></i> نشط
                            </div>

                            {{-- Countdown --}}
                            <div class="countdown-overlay">
                                <div class="countdown-timer"
                                     data-deadline="{{ $auction->deadline->toIso8601String() }}"
                                     id="timer-{{ $auction->id }}">
                                    <div class="countdown-block">
                                        <span class="countdown-num" data-days>00</span>
                                        <span class="countdown-lbl">يوم</span>
                                    </div>
                                    <span class="countdown-sep">:</span>
                                    <div class="countdown-block">
                                        <span class="countdown-num" data-hours>00</span>
                                        <span class="countdown-lbl">ساعة</span>
                                    </div>
                                    <span class="countdown-sep">:</span>
                                    <div class="countdown-block">
                                        <span class="countdown-num" data-mins>00</span>
                                        <span class="countdown-lbl">دقيقة</span>
                                    </div>
                                    <span class="countdown-sep">:</span>
                                    <div class="countdown-block">
                                        <span class="countdown-num" data-secs>00</span>
                                        <span class="countdown-lbl">ثانية</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="auction-card-body">
                            @if($auction->product->category)
                                <div class="auction-card-cat">{{ $auction->product->category->name }}</div>
                            @endif
                            <h3 class="auction-card-title">{{ $auction->product->title }}</h3>

                            <div class="auction-card-meta">
                                <div>
                                    <div class="auction-price-label">السعر التقديري</div>
                                    <div class="auction-price">{{ number_format($auction->estimated_price, 0) }} ر.س</div>
                                </div>
                                <div class="auction-interest-count">
                                    <i class="fa-solid fa-users fa-xs"></i>
                                    {{ $auction->interests_count }} مهتم
                                </div>
                            </div>

                            <button
                                class="btn-register-interest"
                                onclick="openInterestModal(
                                    {{ $auction->id }},
                                    '{{ addslashes($auction->product->title) }}',
                                    '{{ number_format($auction->estimated_price, 0) }}',
                                    '{{ $auction->product->images->isNotEmpty() ? Storage::url($auction->product->images->first()->url) : '' }}'
                                )"
                            >
                                <i class="fa-solid fa-gavel fa-sm"></i>
                                سجّل اهتمامك
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ══ Closed Auctions ══ --}}
        @if($closedAuctions->isNotEmpty())
            <div class="closed-section">
                <div class="section-header" style="text-align:right;margin-bottom:1.5rem;">
                    <h3 class="section-title" style="font-size:1.3rem;color:var(--color-text-muted);">
                        مزادات سابقة
                    </h3>
                </div>
                <div class="closed-grid">
                    @foreach($closedAuctions as $closed)
                        <div class="closed-card">
                            <div class="closed-card-img">
                                @if($closed->product->images->isNotEmpty())
                                    <img src="{{ Storage::url($closed->product->images->first()->url) }}"
                                         alt="{{ $closed->product->title }}">
                                @endif
                            </div>
                            <div class="closed-card-body">
                                <div class="closed-badge">
                                    <i class="fa-solid fa-lock fa-xs"></i> انتهى
                                </div>
                                <div class="closed-card-title">{{ $closed->product->title }}</div>
                                <div class="closed-card-meta">
                                    {{ $closed->interests_count }} مهتم ·
                                    {{ number_format($closed->estimated_price, 0) }} ر.س
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ══ Interest Modal ══ --}}
<div class="modal-overlay" id="interestModal" onclick="closeModalOut(event)">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-title">
                <i class="fa-solid fa-gavel" style="color:var(--color-gold);margin-left:.4rem;"></i>
                تسجيل الاهتمام بالمزاد
            </div>
            <button class="modal-close" onclick="closeModal()" type="button">✕</button>
        </div>

        {{-- معاينة المنتج --}}
        <div class="modal-product-preview">
            <div class="modal-product-img">
                <img id="modalProductImg" src="" alt="" style="display:none;">
            </div>
            <div>
                <div class="modal-product-name" id="modalProductName"></div>
                <div class="modal-product-price">السعر التقديري: <span id="modalProductPrice"></span> ر.س</div>
            </div>
        </div>

        <form action="{{ route('auction.interest') }}" method="POST">
            @csrf
            <input type="hidden" name="auction_item_id" id="auctionItemId">

            <div class="modal-body">
                <div class="form-row-2">
                    <div class="form-group-m">
                        <label class="form-label-m">الاسم الكامل <span style="color:#DC2626">*</span></label>
                        <input type="text" name="name" class="form-input-m"
                            value="{{ auth()->user()?->name }}" placeholder="محمد عبدالله" required>
                    </div>
                    <div class="form-group-m">
                        <label class="form-label-m">رقم الهاتف <span style="color:#DC2626">*</span></label>
                        <input type="tel" name="phone" class="form-input-m"
                            value="{{ auth()->user()?->phone }}" placeholder="+966 5X XXX XXXX" required>
                    </div>
                </div>

                <div class="form-group-m">
                    <label class="form-label-m">البريد الإلكتروني <span style="color:#DC2626">*</span></label>
                    <input type="email" name="email" class="form-input-m"
                        value="{{ auth()->user()?->email }}" placeholder="example@email.com" required>
                </div>

                <div class="form-group-m">
                    <label class="form-label-m">ميزانيتك القصوى (ر.س) <span style="color:#DC2626">*</span></label>
                    <input type="number" name="max_budget" class="form-input-m"
                        placeholder="مثال: 15000" min="1" required>
                    <div style="font-size:.75rem;color:var(--color-text-muted);margin-top:.3rem;">
                        ستُستخدم للتواصل معك بعروض ضمن ميزانيتك
                    </div>
                </div>

                <div class="form-group-m">
                    <label class="form-label-m">ملاحظات إضافية</label>
                    <textarea name="notes" class="form-input-m" style="resize:vertical;min-height:75px;"
                        placeholder="أي تفاصيل إضافية أو استفسارات..."></textarea>
                </div>

                <button type="submit" class="btn-submit-modal">
                    <i class="fa-solid fa-check-circle fa-sm"></i> تأكيد التسجيل
                </button>

                <p style="text-align:center;font-size:.75rem;color:var(--color-text-muted);margin-top:.875rem;">
                    <i class="fa-solid fa-shield-halved fa-xs" style="color:var(--color-gold);"></i>
                    بياناتك محمية ولن تُشارك مع أي طرف ثالث
                </p>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─── Modal ───
function openInterestModal(id, name, price, img) {
    document.getElementById('auctionItemId').value = id;
    document.getElementById('modalProductName').textContent  = name;
    document.getElementById('modalProductPrice').textContent = price;
    const imgEl = document.getElementById('modalProductImg');
    if (img) { imgEl.src = img; imgEl.style.display = 'block'; }
    document.getElementById('interestModal').classList.add('open');
}
function closeModal()       { document.getElementById('interestModal').classList.remove('open'); }
function closeModalOut(e)   { if (e.target.id === 'interestModal') closeModal(); }

// ─── Countdown Timers ───
function startCountdowns() {
    document.querySelectorAll('.countdown-timer[data-deadline]').forEach(timer => {
        function tick() {
            const deadline = new Date(timer.dataset.deadline).getTime();
            const now      = Date.now();
            const diff     = deadline - now;

            if (diff <= 0) {
                timer.innerHTML = '<span style="color:rgba(255,255,255,.6);font-size:.8rem;">انتهى المزاد</span>';
                return;
            }

            const d = Math.floor(diff / 86400000);
            const h = Math.floor((diff % 86400000) / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            const pad = n => String(n).padStart(2, '0');

            timer.querySelector('[data-days]').textContent  = pad(d);
            timer.querySelector('[data-hours]').textContent = pad(h);
            timer.querySelector('[data-mins]').textContent  = pad(m);
            timer.querySelector('[data-secs]').textContent  = pad(s);
        }
        tick();
        setInterval(tick, 1000);
    });
}
startCountdowns();
</script>
@endpush
