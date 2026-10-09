@extends('layouts.app')
@section('title', 'تسجيل الدخول')
@section('noindex', true)

@include('auth.partials.styles')

@section('content')
<div class="auth-page">
    <div class="auth-card">

        {{-- ─── Header ─── --}}
        <div class="auth-header">
            <div class="auth-logo">قطعة <span>وقصة</span></div>
            <h2>تسجيل الدخول</h2>
            <p>أهلاً بك — سجّل دخولك للمتابعة</p>
        </div>

        {{-- ─── Form ─── --}}
        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            {{-- البريد الإلكتروني --}}
            <div class="form-group">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="example@email.com"
                    autocomplete="email"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- كلمة المرور --}}
            <div class="form-group">
                <label class="form-label" for="password">كلمة المرور</label>
                <div style="position:relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button"
                        onclick="togglePassword()"
                        style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- تذكرني + نسيت كلمة المرور --}}
            <div class="form-row">
                <label class="form-check">
                    <input type="checkbox" name="remember">
                    تذكرني
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">نسيت كلمة المرور؟</a>
            </div>

            {{-- زر الدخول --}}
            <button type="submit" class="btn-submit">
                دخول
            </button>
        </form>

        <div class="auth-divider">أو</div>

        <div class="auth-footer">
            ليس لديك حساب؟
            <a href="{{ route('register') }}">إنشاء حساب جديد</a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}
</script>
@endpush
