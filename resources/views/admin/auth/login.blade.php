<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول الإدارة — قطعة وقصة</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
            background: #1A0F08;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-wrap {
            display: flex;
            width: 100%;
            max-width: 900px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.5);
        }

        /* ─── Panel يسار: تصميمي ─── */
        .login-brand {
            flex: 1;
            background: linear-gradient(160deg, #3A2418 0%, #2A1A10 100%);
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            border: 60px solid rgba(182,138,74,.08);
            border-radius: 50%;
        }

        .login-brand::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border: 40px solid rgba(182,138,74,.06);
            border-radius: 50%;
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #B68A4A;
            margin-bottom: 1rem;
        }

        .brand-logo span { color: #fff; }

        .brand-tagline {
            color: rgba(255,255,255,.6);
            font-size: .95rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .brand-divider {
            width: 48px; height: 3px;
            background: #B68A4A;
            border-radius: 2px;
            margin-bottom: 2rem;
        }

        .brand-links a {
            display: flex; align-items: center; gap: .5rem;
            color: rgba(255,255,255,.4);
            font-size: .85rem;
            margin-bottom: .6rem;
            text-decoration: none;
            transition: color .2s;
        }

        .brand-links a:hover { color: #B68A4A; }

        /* ─── Form Panel ─── */
        .login-form-panel {
            width: 380px;
            background: #fff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #3A2418;
            margin-bottom: .4rem;
        }

        .form-subtitle {
            color: #8B7355;
            font-size: .875rem;
            margin-bottom: 2rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: .8rem;
            font-weight: 700;
            color: #3A2418;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .form-input {
            width: 100%;
            padding: .8rem 1rem;
            border: 1.5px solid #E2D9CE;
            border-radius: 8px;
            font-family: inherit;
            font-size: .9rem;
            color: #2C1810;
            background: #FAF8F5;
            direction: rtl;
            transition: all .2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #B68A4A;
            box-shadow: 0 0 0 3px rgba(182,138,74,.12);
            background: #fff;
        }

        .form-input.error { border-color: #DC2626; }

        .error-msg {
            color: #DC2626;
            font-size: .8rem;
            margin-top: .3rem;
            display: flex; align-items: center; gap: .3rem;
        }

        .btn-login {
            width: 100%;
            padding: .9rem;
            background: #3A2418;
            color: #B68A4A;
            border: none; border-radius: 8px;
            font-family: inherit; font-size: 1rem; font-weight: 700;
            cursor: pointer; margin-top: .5rem;
            transition: all .25s;
        }

        .btn-login:hover {
            background: #B68A4A;
            color: #3A2418;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(182,138,74,.3);
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: #8B7355; font-size: .85rem;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
        }

        .back-link a:hover { color: #3A2418; }

        @media (max-width: 700px) {
            .login-brand { display: none; }
            .login-form-panel { width: 100%; }
        }
    </style>
</head>
<body>

<div class="login-wrap">
    {{-- ─── Panel يسار ─── --}}
    <div class="login-brand">
        <div class="brand-logo">قطعة <span>وقصة</span></div>
        <p class="brand-tagline">كل قطعة تحمل روحاً وتروي قصة.<br>مرحباً بك في لوحة إدارة المعرض.</p>
        <div class="brand-divider"></div>
        <div class="brand-links">
            <a href="{{ route('home') }}" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square fa-xs"></i> عرض الموقع
            </a>
            <a href="{{ route('shop') }}" target="_blank">
                <i class="fa-solid fa-bag-shopping fa-xs"></i> المتجر
            </a>
        </div>
    </div>

    {{-- ─── Form Panel ─── --}}
    <div class="login-form-panel">
        <h2 class="form-title">دخول الإدارة</h2>
        <p class="form-subtitle">للمصرّح لهم فقط</p>

        <form action="{{ route('admin.login') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input
                    type="email" id="email" name="email"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="admin@qet3a-w-qesa.sa"
                    autocomplete="email" required autofocus
                >
                @error('email')
                    <div class="error-msg">
                        <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">كلمة المرور</label>
                <div style="position:relative;">
                    <input
                        type="password" id="password" name="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••"
                        autocomplete="current-password" required
                    >
                    <button type="button" onclick="toggle()"
                        style="position:absolute;left:.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#8B7355;">
                        <i class="fa-regular fa-eye" id="eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-msg">
                        <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> دخول
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-chevron-right fa-xs"></i> العودة للموقع
            </a>
        </div>
    </div>
</div>

<script>
function toggle() {
    const p = document.getElementById('password');
    const e = document.getElementById('eye');
    p.type = p.type === 'password' ? 'text' : 'password';
    e.className = p.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
}
</script>
</body>
</html>
