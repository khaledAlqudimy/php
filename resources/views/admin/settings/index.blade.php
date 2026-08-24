@extends('layouts.admin')
@section('title','الإعدادات')
@section('page-title','إعدادات الموقع')

@push('styles')
<style>
.settings-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(460px,1fr));gap:1.5rem; }
.set-card { background:var(--white);border-radius:var(--radius);border:1px solid var(--border);overflow:hidden; }
.set-head { padding:1rem 1.25rem;border-bottom:1px solid var(--border);font-weight:700;font-size:.875rem;color:var(--brown);display:flex;align-items:center;gap:.5rem; }
.set-head i { color:var(--gold); }
.set-body { padding:1.25rem;display:flex;flex-direction:column;gap:1rem; }
.set-field label { display:block;font-size:.78rem;font-weight:700;color:var(--brown);margin-bottom:.3rem; }
.set-input { width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:var(--radius);font-family:var(--font);font-size:.875rem;color:var(--text);background:var(--bg);direction:rtl;transition:all .2s; }
.set-input:focus { outline:none;border-color:var(--gold);background:var(--white); }
.set-row-2 { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
.toggle-wrap { display:flex;align-items:center;gap:.75rem;font-size:.875rem;color:var(--text); }
.toggle-input { position:relative;display:inline-block;width:44px;height:24px; }
.toggle-input input { opacity:0;width:0;height:0; }
.toggle-slider { position:absolute;inset:0;background:#CBD5E1;border-radius:100px;cursor:pointer;transition:.3s; }
.toggle-slider::before { content:'';position:absolute;width:18px;height:18px;background:#fff;border-radius:50%;bottom:3px;left:3px;transition:.3s;box-shadow:0 1px 3px rgba(0,0,0,.2); }
.toggle-input input:checked + .toggle-slider { background:var(--gold); }
.toggle-input input:checked + .toggle-slider::before { transform:translateX(20px); }
.btn-save-settings { display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.875rem 2rem;background:var(--brown);color:var(--gold);border:none;border-radius:var(--radius);font-family:var(--font);font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s;margin-top:1.5rem; }
.btn-save-settings:hover { background:var(--gold);color:var(--brown); }
</style>
@endpush

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf @method('PUT')

    <div class="settings-grid">

        {{-- معلومات الموقع --}}
        <div class="set-card">
            <div class="set-head"><i class="fa-solid fa-globe fa-sm"></i> معلومات الموقع</div>
            <div class="set-body">
                @foreach([['site_name','اسم الموقع','قطعة وقصة'],['site_tagline','الشعار','كل قطعة تحمل روحاً وتروي قصة'],['site_email','البريد الإلكتروني','info@...'],['site_phone','رقم الهاتف','+966 XX XXX XXXX'],['site_whatsapp','واتساب','+966XXXXXXXXX']] as [$key,$label,$ph])
                    <div class="set-field">
                        <label>{{ $label }}</label>
                        <input type="text" name="{{ $key }}" class="set-input"
                            value="{{ $settings[$key] ?? '' }}" placeholder="{{ $ph }}">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- الشحن والمالية --}}
        <div class="set-card">
            <div class="set-head"><i class="fa-solid fa-truck-fast fa-sm"></i> الشحن والمالية</div>
            <div class="set-body">
                <div class="set-row-2">
                    <div class="set-field">
                        <label>رسوم الشحن (ر.س)</label>
                        <input type="number" name="shipping_fee" class="set-input" value="{{ $settings['shipping_fee'] ?? 30 }}" min="0">
                    </div>
                    <div class="set-field">
                        <label>الحد الأدنى للشحن المجاني</label>
                        <input type="number" name="free_shipping_min" class="set-input" value="{{ $settings['free_shipping_min'] ?? 500 }}" min="0">
                    </div>
                </div>
                <div class="set-row-2">
                    <div class="set-field">
                        <label>أقل مدة توصيل (أيام)</label>
                        <input type="number" name="delivery_days_min" class="set-input" value="{{ $settings['delivery_days_min'] ?? 3 }}" min="1">
                    </div>
                    <div class="set-field">
                        <label>أقصى مدة توصيل (أيام)</label>
                        <input type="number" name="delivery_days_max" class="set-input" value="{{ $settings['delivery_days_max'] ?? 7 }}" min="1">
                    </div>
                </div>
                <div class="set-row-2">
                    <div class="set-field">
                        <label>نسبة ضريبة القيمة المضافة (%)</label>
                        <input type="number" name="vat_percent" class="set-input" value="{{ $settings['vat_percent'] ?? 15 }}" min="0" max="100">
                    </div>
                    <div class="set-field">
                        <label>تفعيل الضريبة</label>
                        <div style="padding-top:.5rem;">
                            <div class="toggle-wrap">
                                <label class="toggle-input">
                                    <input type="hidden" name="vat_enabled" value="0">
                                    <input type="checkbox" name="vat_enabled" value="1" {{ ($settings['vat_enabled'] ?? 1) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span>مفعّلة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- السوشيال --}}
        <div class="set-card">
            <div class="set-head"><i class="fa-solid fa-share-nodes fa-sm"></i> وسائل التواصل الاجتماعي</div>
            <div class="set-body">
                @foreach([['social_instagram','انستقرام','@username'],['social_twitter','تويتر / X','@username'],['social_facebook','فيسبوك','رابط الصفحة'],['social_snapchat','سناب شات','@username']] as [$key,$label,$ph])
                    <div class="set-field">
                        <label>{{ $label }}</label>
                        <input type="text" name="{{ $key }}" class="set-input"
                            value="{{ $settings[$key] ?? '' }}" placeholder="{{ $ph }}">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- الخدمات --}}
        <div class="set-card">
            <div class="set-head"><i class="fa-solid fa-sliders fa-sm"></i> تفعيل الخدمات</div>
            <div class="set-body">
                @foreach([['auction_enabled','المزادات'],['personal_shopper_enabled','الباحث الشخصي']] as [$key,$label])
                    <div class="toggle-wrap">
                        <label class="toggle-input">
                            <input type="hidden" name="{{ $key }}" value="0">
                            <input type="checkbox" name="{{ $key }}" value="1" {{ ($settings[$key] ?? 1) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <button type="submit" class="btn-save-settings">
        <i class="fa-solid fa-floppy-disk fa-sm"></i> حفظ جميع الإعدادات
    </button>
</form>
@endsection
