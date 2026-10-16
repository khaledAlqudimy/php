<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    // ─── عدد المحاولات المسموح بها ───
    private const MAX_ATTEMPTS = 5;
    private const DECAY_MINUTES = 15;

    // ══════════════════════════════════════════
    //  تسجيل الدخول
    // ══════════════════════════════════════════

    public function showLogin(Request $request)
    {
        // العودة للصفحة التي جاء منها الزائر بعد الدخول (مسارات داخلية فقط — منع التحويل لمواقع خارجية)
        $to = (string) $request->query('redirect');
        if (str_starts_with($to, '/') && ! str_starts_with($to, '//') && ! str_contains($to, '\\')) {
            $request->session()->put('url.intended', url($to));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // ─── Rate Limiting ───
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "عدد كبير من المحاولات. حاول مرة أخرى بعد {$seconds} ثانية.",
            ])->withInput($request->only('email'));
        }

        // ─── التحقق من البيانات ───
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, self::DECAY_MINUTES * 60);

            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ])->withInput($request->only('email'));
        }

        // ─── التحقق من أن الحساب نشط ───
        if (! Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'هذا الحساب موقوف. تواصل مع الدعم.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        // المدير → لوحة الإدارة، العميل → الصفحة الرئيسية
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'مرحباً بك، ' . Auth::user()->name . '!');
    }

    // ══════════════════════════════════════════
    //  إنشاء حساب جديد
    // ══════════════════════════════════════════

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ], [
            'name.required'         => 'الاسم مطلوب',
            'email.required'        => 'البريد الإلكتروني مطلوب',
            'email.unique'          => 'هذا البريد الإلكتروني مسجل مسبقاً',
            'password.required'     => 'كلمة المرور مطلوبة',
            'password.min'          => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed'    => 'كلمتا المرور غير متطابقتان',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'CUSTOMER',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'تم إنشاء حسابك بنجاح. مرحباً بك!');
    }

    // ══════════════════════════════════════════
    //  تسجيل الخروج
    // ══════════════════════════════════════════

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
