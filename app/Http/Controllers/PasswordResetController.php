<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // ══════════════════════════════════════════
    //  نسيت كلمة المرور — طلب رابط
    // ══════════════════════════════════════════
    public function showRequest()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email'    => 'صيغة البريد الإلكتروني غير صحيحة',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput()->withErrors(['email' => 'طلبت رابطاً قبل قليل. انتظر دقيقة ثم حاول مرة أخرى.']);
        }

        // نفس الرسالة سواء كان البريد مسجلاً أم لا — حتى لا يُكشف من لديه حساب
        return back()->with('status', 'إذا كان البريد مسجلاً لدينا فستصلك رسالة برابط إعادة تعيين كلمة المرور خلال دقائق.');
    }

    // ══════════════════════════════════════════
    //  إعادة التعيين
    // ══════════════════════════════════════════
    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'email.required'     => 'البريد الإلكتروني مطلوب',
            'password.required'  => 'كلمة المرور مطلوبة',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمتا المرور غير متطابقتان',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => $password,   // يُشفَّر تلقائياً (cast: hashed)
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'رابط إعادة التعيين غير صالح أو منتهي الصلاحية. اطلب رابطاً جديداً.',
            ]);
        }

        return redirect()->route('login')->with('success', 'تم تغيير كلمة المرور بنجاح. سجّل دخولك بكلمة المرور الجديدة.');
    }
}
