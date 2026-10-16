@extends('layouts.app')
@section('title', 'إعادة تعيين كلمة المرور')
@section('noindex', true)

@include('auth.partials.styles')

@section('content')
<div class="auth-page">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-logo">قطعة <span>وقصة</span></div>
            <h2>كلمة مرور جديدة</h2>
            <p>اختر كلمة مرور قوية لا تقل عن 8 أحرف</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $email) }}"
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

            <div class="form-group">
                <label class="form-label" for="password">كلمة المرور الجديدة</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit" class="btn-submit">حفظ كلمة المرور</button>
        </form>

    </div>
</div>
@endsection
