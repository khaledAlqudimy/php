<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS  = 5;
    private const DECAY_MINUTES = 15;

    public function showLogin()
    {
        // إذا كان مسجّل دخول وهو مدير → لوحة التحكم مباشرة
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // ─── Rate Limiting — أشد صرامة للإدارة ───
        $throttleKey = 'admin|' . Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "محظور مؤقتاً. حاول بعد {$seconds} ثانية.",
            ]);
        }

        // ─── محاولة الدخول ───
        if (! Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($throttleKey, self::DECAY_MINUTES * 60);
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ])->withInput($request->only('email'));
        }

        // ─── التحقق من الصلاحية ───
        if (! Auth::user()->isAdmin()) {
            Auth::logout();
            RateLimiter::hit($throttleKey, self::DECAY_MINUTES * 60);
            return back()->withErrors([
                'email' => 'ليس لديك صلاحية الدخول للوحة الإدارة.',
            ]);
        }

        if (! Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'هذا الحساب موقوف.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'مرحباً ' . Auth::user()->name);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
