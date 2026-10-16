@extends('layouts.app')
@section('title', 'الباحث الشخصي')
@section('meta_description', 'خدمة الباحث الشخصي: أخبرنا بالقطعة التي تبحث عنها وفريقنا يجدها لك من مصادر موثوقة حول العالم.')

@push('styles')
<style>
/* ══ Personal Shopper Page ══ */
.shopper-hero {
    background: linear-gradient(135deg, #0F1E2E 0%, #1A2A3A 100%);
    padding: 5rem 0 4rem;
    position: relative; overflow: hidden; text-align: center;
}
.shopper-hero::before {
    content: '';
    position: absolute; top: -80px; left: 50%;
    transform: translateX(-50%);
    width: 500px; height: 500px;
    border: 80px solid rgba(182,138,74,.05);
    border-radius: 50%;
}

.shopper-hero-inner { position: relative; z-index: 1; }

.shopper-hero-badge {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(182,138,74,.15);
    border: 1px solid rgba(182,138,74,.3);
    color: var(--color-gold);
    font-size: .8rem; font-weight: 700;
    padding: .4rem 1.25rem; border-radius: 100px;
    margin-bottom: 1.5rem;
}

.shopper-hero h1 {
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 700; color: #fff; margin-bottom: 1rem; line-height: 1.2;
}
.shopper-hero h1 span { color: var(--color-gold); }

.shopper-hero p {
    color: rgba(255,255,255,.6); font-size: .95rem;
    max-width: 520px; margin: 0 auto; line-height: 1.8;
}

/* Features Row */
.shopper-features {
    display: flex; gap: 2rem;
    justify-content: center; flex-wrap: wrap;
    margin-top: 2.5rem;
}
.shopper-feature {
    display: flex; align-items: center; gap: .6rem;
    color: rgba(255,255,255,.65); font-size: .85rem;
}
.shopper-feature i { color: var(--color-gold); }

/* ══ Main Layout ══ */
.shopper-page { padding: 4rem 0 5rem; }

.shopper-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2.5rem;
    align-items: start;
}

/* ══ Form Card ══ */
.form-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    overflow: hidden;
}

.form-card-head {
    padding: 1.25rem 1.75rem;
    background: var(--color-brown);
    display: flex; align-items: center; gap: .6rem;
    font-weight: 700; color: var(--color-gold); font-size: .95rem;
}

.form-card-body { padding: 1.75rem; }

.form-group { margin-bottom: 1.25rem; }

.form-label {
    display: block; font-size: .825rem; font-weight: 700;
    color: var(--color-brown); margin-bottom: .4rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%; padding: .75rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .9rem;
    color: var(--color-text); background: var(--color-bg);
    direction: rtl; transition: var(--transition);
}
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none; border-color: var(--color-gold);
    background: #fff; box-shadow: 0 0 0 3px rgba(182,138,74,.12);
}
.form-input.error, .form-textarea.error { border-color: #DC2626; }

.form-select { cursor: pointer; }
.form-textarea { resize: vertical; min-height: 130px; line-height: 1.7; }

.form-hint { font-size: .75rem; color: var(--color-text-muted); margin-top: .35rem; }
.form-required { color: #DC2626; }

.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

.error-msg {
    color: #DC2626; font-size: .78rem; margin-top: .3rem;
    display: flex; align-items: center; gap: .25rem;
}

/* Item Type Quick Select */
.item-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
    margin-bottom: .75rem;
}
.item-type-chip {
    padding: .5rem .4rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    text-align: center; font-size: .78rem; font-weight: 600;
    color: var(--color-text-muted);
    cursor: pointer; transition: var(--transition);
    background: var(--color-bg);
}
.item-type-chip:hover { border-color: var(--color-gold); color: var(--color-gold); }
.item-type-chip.selected { border-color: var(--color-gold); background: rgba(182,138,74,.08); color: var(--color-gold); }

/* Submit */
.btn-submit-shopper {
    width: 100%; padding: 1rem;
    background: var(--color-brown); color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    margin-top: .5rem;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
}
.btn-submit-shopper:hover {
    background: var(--color-gold); color: var(--color-brown);
    transform: translateY(-2px); box-shadow: var(--shadow-md);
}

/* ══ Info Sidebar ══ */
.info-sidebar { display: flex; flex-direction: column; gap: 1.25rem; }

.info-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    padding: 1.5rem;
}

.info-card-title {
    font-weight: 700; color: var(--color-brown);
    margin-bottom: 1rem; font-size: .95rem;
    display: flex; align-items: center; gap: .5rem;
}
.info-card-title i { color: var(--color-gold); }

.how-step-item {
    display: flex; gap: .75rem; margin-bottom: .875rem;
}
.how-step-item:last-child { margin: 0; }
.step-icon {
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(182,138,74,.1);
    border: 1px solid rgba(182,138,74,.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--color-gold); font-size: .8rem; flex-shrink: 0;
}
.step-text { font-size: .85rem; color: var(--color-text-muted); line-height: 1.6; }
.step-text strong { color: var(--color-brown); display: block; margin-bottom: .1rem; }

.promise-item {
    display: flex; align-items: flex-start; gap: .6rem;
    padding: .6rem 0; border-bottom: 1px solid var(--color-border);
    font-size: .85rem;
}
.promise-item:last-child { border: none; padding-bottom: 0; }
.promise-item i { color: var(--color-gold); margin-top: .2rem; flex-shrink: 0; }
.promise-text { color: var(--color-text-muted); line-height: 1.5; }

/* Success Banner */
.success-banner {
    background: #DCFCE7; border: 1px solid #BBF7D0;
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    display: flex; gap: 1rem; align-items: flex-start;
    margin-bottom: 2rem;
}
.success-icon {
    width: 44px; height: 44px; border-radius: 50%;
    background: #166534; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.success-text h3 { font-size: .95rem; font-weight: 700; color: #166534; margin-bottom: .25rem; }
.success-text p  { font-size: .85rem; color: #166534; opacity: .85; }

@media (max-width: 900px) {
    .shopper-grid { grid-template-columns: 1fr; }
    .form-row-2 { grid-template-columns: 1fr; }
    .item-type-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

{{-- ══ Hero ══ --}}
<div class="shopper-hero">
    <div class="container shopper-hero-inner">
        <div class="shopper-hero-badge">
            <i class="fa-solid fa-magnifying-glass fa-xs"></i> خدمة مميزة
        </div>
        <h1>نبحث عن <span>قطعتك الأثيرة</span><br>في أرجاء العالم</h1>
        <p>أخبرنا عن القطعة التي تبحث عنها وسيتولى فريق خبرائنا البحث عنها من مصادر حصرية حول العالم.</p>

        <div class="shopper-features">
            @foreach([
                ['fa-globe',          'شبكة عالمية واسعة'],
                ['fa-user-tie',       'خبراء متخصصون'],
                ['fa-shield-halved',  'ضمان الأصالة'],
                ['fa-clock',          'رد خلال 48 ساعة'],
            ] as [$icon, $text])
                <div class="shopper-feature">
                    <i class="fa-solid {{ $icon }} fa-sm"></i> {{ $text }}
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══ Main Content ══ --}}
<div class="shopper-page">
    <div class="container">

        {{-- Success --}}
        @if(session('success'))
            <div class="success-banner">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <div class="success-text">
                    <h3>تم استلام طلبك بنجاح!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="shopper-grid">

            {{-- ══ Form ══ --}}
            <div class="form-card">
                <div class="form-card-head">
                    <i class="fa-solid fa-pen-to-square fa-sm"></i>
                    أخبرنا عن القطعة التي تبحث عنها
                </div>
                <div class="form-card-body">
                    <form action="{{ route('personal-shopper.store') }}" method="POST" novalidate>
                        @csrf

                        {{-- نوع القطعة --}}
                        <div class="form-group">
                            <label class="form-label">
                                نوع القطعة <span class="form-required">*</span>
                            </label>

                            {{-- اختيار سريع --}}
                            <div class="item-type-grid" id="typeChips">
                                @foreach(['أثاث كلاسيكي','ساعة أنتيك','لوحة فنية','تحفة نحاسية','مجوهرات قديمة','أخرى'] as $type)
                                    <div class="item-type-chip" onclick="selectType('{{ $type }}', this)">
                                        {{ $type }}
                                    </div>
                                @endforeach
                            </div>

                            <input
                                type="text" name="item_type" id="itemTypeInput"
                                class="form-input {{ $errors->has('item_type') ? 'error' : '' }}"
                                placeholder="أو اكتب نوع القطعة هنا..."
                                value="{{ old('item_type') }}" required
                            >
                            @error('item_type')
                                <div class="error-msg"><i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الحقبة والبلد --}}
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">الحقبة الزمنية</label>
                                <select name="era" class="form-select">
                                    <option value="">غير محدد</option>
                                    @foreach([
                                        'القرن السابع عشر (1600-1699)',
                                        'القرن الثامن عشر (1700-1799)',
                                        'القرن التاسع عشر (1800-1899)',
                                        'مطلع القرن العشرين (1900-1950)',
                                        'منتصف القرن العشرين (1950-1980)',
                                        'الفترة العثمانية',
                                        'الفترة الفيكتورية',
                                        'عصر النهضة',
                                        'ما قبل 1600',
                                    ] as $era)
                                        <option value="{{ $era }}" {{ old('era') === $era ? 'selected' : '' }}>{{ $era }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">البلد الأصلي</label>
                                <input
                                    type="text" name="country_of_origin"
                                    class="form-input"
                                    placeholder="مثال: فرنسا، إيطاليا..."
                                    value="{{ old('country_of_origin') }}"
                                >
                            </div>
                        </div>

                        {{-- الميزانية --}}
                        <div class="form-group">
                            <label class="form-label">الميزانية القصوى (ر.س)</label>
                            <input
                                type="number" name="budget"
                                class="form-input"
                                placeholder="مثال: 50000"
                                min="0" value="{{ old('budget') }}"
                            >
                            <div class="form-hint">اتركها فارغة إذا كانت الميزانية مفتوحة</div>
                        </div>

                        {{-- الوصف --}}
                        <div class="form-group">
                            <label class="form-label">
                                وصف القطعة <span class="form-required">*</span>
                            </label>
                            <textarea
                                name="description"
                                class="form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                                placeholder="اشرح لنا القطعة التي تبحث عنها بأكبر قدر من التفاصيل: الشكل، الحجم، الخامة، الغرض منها، لماذا تريدها..."
                                required
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="error-msg"><i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}</div>
                            @enderror
                            <div class="form-hint">كلما كان الوصف أدق، كانت فرصة إيجاد القطعة المثالية أعلى</div>
                        </div>

                        {{-- بيانات التواصل (للزوار غير المسجّلين) --}}
                        @guest
                            <div style="background:rgba(182,138,74,.06);border:1px solid rgba(182,138,74,.2);border-radius:var(--radius);padding:1.1rem;margin-bottom:1.25rem;">
                                <div style="font-size:.8rem;font-weight:700;color:var(--color-brown);margin-bottom:.875rem;">
                                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--color-gold);"></i>
                                    بيانات التواصل
                                </div>
                                <div class="form-row-2">
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label">الاسم</label>
                                        <input type="text" name="guest_name" class="form-input" placeholder="اسمك الكامل">
                                    </div>
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label">البريد / الواتساب</label>
                                        <input type="text" name="guest_contact" class="form-input" placeholder="+966...">
                                    </div>
                                </div>
                            </div>
                        @endguest

                        <button type="submit" class="btn-submit-shopper">
                            <i class="fa-solid fa-paper-plane fa-sm"></i>
                            إرسال الطلب
                        </button>

                        <p style="text-align:center;font-size:.75rem;color:var(--color-text-muted);margin-top:.75rem;">
                            <i class="fa-solid fa-shield-halved fa-xs" style="color:var(--color-gold);"></i>
                            طلبك سري تماماً ولن تُشارك بياناتك
                        </p>
                    </form>
                </div>
            </div>

            {{-- ══ Sidebar ══ --}}
            <div class="info-sidebar">

                {{-- كيف يعمل --}}
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fa-solid fa-circle-question"></i> كيف تعمل الخدمة؟
                    </div>
                    @foreach([
                        ['fa-pen',           'أرسل طلبك',       'أخبرنا عن القطعة التي تبحث عنها بأكبر قدر من التفاصيل'],
                        ['fa-magnifying-glass','نبدأ البحث',     'يبحث خبراؤنا في شبكتنا الواسعة من الموردين حول العالم'],
                        ['fa-phone',          'نتواصل معك',      'نرسل لك الخيارات المتاحة مع الأسعار خلال 48 ساعة'],
                        ['fa-handshake',      'تحصل على قطعتك', 'تختار وتدفع ونتولى الشحن حتى بابك'],
                    ] as [$icon, $title, $desc])
                        <div class="how-step-item">
                            <div class="step-icon"><i class="fa-solid {{ $icon }} fa-xs"></i></div>
                            <div class="step-text">
                                <strong>{{ $title }}</strong>{{ $desc }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- وعودنا --}}
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fa-solid fa-star"></i> وعودنا لك
                    </div>
                    @foreach([
                        ['fa-clock',          'رد مبدئي خلال 48 ساعة'],
                        ['fa-shield-halved',  'ضمان أصالة كل قطعة'],
                        ['fa-tag',            'أسعار منافسة وشفافة'],
                        ['fa-truck-fast',     'شحن آمن لأي مكان في العالم'],
                        ['fa-rotate-left',    'إمكانية الإرجاع إن لم تكن راضياً'],
                    ] as [$icon, $text])
                        <div class="promise-item">
                            <i class="fa-solid {{ $icon }} fa-sm"></i>
                            <div class="promise-text">{{ $text }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- تواصل --}}
                @php $waLink = \App\Models\Setting::whatsappLink(); @endphp
                @if($waLink)
                    <div class="info-card" style="background:linear-gradient(135deg,#0a4c2e,#166534);border-color:#166534;">
                        <div style="color:#fff;font-weight:700;margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">
                            <i class="fa-brands fa-whatsapp fa-lg" style="color:#4ade80;"></i>
                            تحدث معنا مباشرة
                        </div>
                        <p style="color:rgba(255,255,255,.75);font-size:.85rem;margin-bottom:1rem;line-height:1.6;">
                            لديك استفسار عاجل؟ تواصل معنا مباشرة عبر واتساب
                        </p>
                        <a href="{{ $waLink }}"
                           target="_blank"
                           style="display:flex;align-items:center;justify-content:center;gap:.5rem;background:#25D366;color:#fff;border-radius:var(--radius);padding:.7rem 1rem;font-weight:700;font-size:.9rem;text-decoration:none;transition:var(--transition);">
                            <i class="fa-brands fa-whatsapp fa-sm"></i> فتح واتساب
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectType(type, chip) {
    document.querySelectorAll('.item-type-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    document.getElementById('itemTypeInput').value = type;
}
</script>
@endpush
