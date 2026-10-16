@extends('layouts.app')
@section('title', 'نسيت كلمة المرور')
@section('noindex', true)

@include('auth.partials.styles')

@section('content')
<div class="auth-page">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">قطعة <span>وقصة</span></div>
            <h2>نسيت كلمة المرور؟</h2>
            <p>أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيينها</p>
        </div>

        @if(session('status'))
            <div style="background:#DCFCE7;color:#166534;border-radius:var(--radius);padding:.875rem 1rem;margin-bottom:1.25rem;font-weight:600;font-size:.875rem;line-height:1.7;">
                <i class="fa-solid fa-envelope-circle-check"></i> {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" novalidate>
            @csrf

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

            <button type="submit" class="btn-submit">إرسال رابط إعادة التعيين</button>
        </form>

        <div class="auth-divider">أو</div>

        <div class="auth-footer">
            تذكرت كلمة المرور؟
            <a href="{{ route('login') }}">تسجيل الدخول</a>
        </div>

    </div>
</div>
@endsection
