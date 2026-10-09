@extends('layouts.app')
@section('title', 'إنشاء حساب')
@section('noindex', true)

@push('styles')
<style>
/* نفس أنماط login مع زيادة الارتفاع */
.auth-page {
    min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-bg-2) 100%);
}

.auth-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    width: 100%;
    max-width: 480px;
    box-shadow: var(--shadow-lg);
    border-top: 4px solid var(--color-gold);
}

.auth-header { text-align: center; margin-bottom: 2rem; }
.auth-logo { font-size: 1.75rem; font-weight: 700; color: var(--color-gold); margin-bottom: .5rem; }
.auth-logo span { color: var(--color-brown); }
.auth-header h2 { font-size: 1.25rem; color: var(--color-brown); font-weight: 600; }
.auth-header p { color: var(--color-text-muted); font-size: .9rem; margin-top: .25rem; }

.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: .875rem; font-weight: 600; color: var(--color-brown); margin-bottom: .4rem; }
.form-input {
    width: 100%; padding: .75rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main); font-size: .9rem;
    color: var(--color-text); background: var(--color-bg);
    transition: var(--transition); direction: rtl;
}
.form-input:focus { outline: none; border-color: var(--color-gold); background: var(--color-white); box-shadow: 0 0 0 3px rgba(182,138,74,.15); }
.form-input.is-invalid { border-color: #DC2626; }
.invalid-feedback { color: #DC2626; font-size: .8rem; margin-top: .3rem; }

.form-hint { color: var(--color-text-muted); font-size: .78rem; margin-top: .3rem; }

.btn-submit {
    width: 100%; padding: .875rem;
    background: var(--color-brown); color: var(--color-gold);
    border: none; border-radius: var(--radius);
    font-family: var(--font-main); font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
}
.btn-submit:hover {
    background: var(--color-gold); color: var(--color-brown);
    transform: translateY(-1px); box-shadow: var(--shadow-md);
}

.auth-divider { display: flex; align-items: center; gap: 1rem; margin: 1.5rem 0; color: var(--color-text-muted); font-size: .8rem; }
.auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--color-border); }

.auth-footer { text-align: center; color: var(--color-text-muted); font-size: .875rem; margin-top: 1.5rem; }
.auth-footer a { color: var(--color-gold); font-weight: 600; }

/* الشروط والأحكام */
.terms-check {
    display: flex; align-items: flex-start; gap: .6rem;
    margin-bottom: 1.5rem; font-size: .85rem; color: var(--color-text-muted);
    cursor: pointer;
}
.terms-check input { accent-color: var(--color-gold); margin-top: .15rem; flex-shrink: 0; }
.terms-check a { color: var(--color-gold); font-weight: 600; }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">قطعة <span>وقصة</span></div>
            <h2>إنشاء حساب جديد</h2>
            <p>انضم إلينا واكتشف قطعاً تحمل تاريخاً</p>
        </div>

        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            {{-- الاسم --}}
            <div class="form-group">
                <label class="form-label" for="name">الاسم الكامل <span style="color:#DC2626">*</span></label>
                <input
                    type="text" id="name" name="name"
                    class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}"
                    placeholder="محمد عبدالله"
                    autocomplete="name" required
                >
                @error('name')
                    <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- البريد --}}
            <div class="form-group">
                <label class="form-label" for="email">البريد الإلكتروني <span style="color:#DC2626">*</span></label>
                <input
                    type="email" id="email" name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="example@email.com"
                    autocomplete="email" required
                >
                @error('email')
                    <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- الجوال --}}
            <div class="form-group">
                <label class="form-label" for="phone">رقم الجوال <span style="color:var(--color-text-muted);font-weight:400;">(اختياري)</span></label>
                <input
                    type="tel" id="phone" name="phone"
                    class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                    value="{{ old('phone') }}"
                    placeholder="+966 5X XXX XXXX"
                    autocomplete="tel"
                >
            </div>

            {{-- كلمة المرور --}}
            <div class="form-group">
                <label class="form-label" for="password">كلمة المرور <span style="color:#DC2626">*</span></label>
                <div style="position:relative;">
                    <input
                        type="password" id="password" name="password"
                        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••"
                        autocomplete="new-password" required
                    >
                    <button type="button" onclick="togglePass('password','eye1')"
                        style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                        <i class="fa-regular fa-eye" id="eye1"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
                <div class="form-hint">8 أحرف على الأقل</div>
            </div>

            {{-- تأكيد كلمة المرور --}}
            <div class="form-group">
                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور <span style="color:#DC2626">*</span></label>
                <div style="position:relative;">
                    <input
                        type="password" id="password_confirmation" name="password_confirmation"
                        class="form-input"
                        placeholder="••••••••"
                        autocomplete="new-password" required
                    >
                    <button type="button" onclick="togglePass('password_confirmation','eye2')"
                        style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                        <i class="fa-regular fa-eye" id="eye2"></i>
                    </button>
                </div>
            </div>

            <div class="terms-check" style="margin-bottom:1.25rem;">
                <i class="fa-solid fa-shield-halved" style="color:var(--color-gold);margin-top:.2rem;"></i>
                <span>بإنشاء حسابك فأنت توافق على <a href="{{ route('terms') }}" target="_blank">الشروط والأحكام</a> و<a href="{{ route('privacy') }}" target="_blank">سياسة الخصوصية</a>.</span>
            </div>

            <button type="submit" class="btn-submit">إنشاء الحساب</button>
        </form>

        <div class="auth-divider">لديك حساب؟</div>

        <div class="auth-footer">
            <a href="{{ route('login') }}">تسجيل الدخول</a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
}
</script>
@endpush
