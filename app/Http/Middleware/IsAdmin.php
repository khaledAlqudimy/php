<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * السماح فقط لـ: SUPER_ADMIN, MANAGER, STAFF
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        $allowedRoles = ['SUPER_ADMIN', 'MANAGER', 'STAFF'];

        if (! in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'غير مصرح لك بالوصول للوحة الإدارة.');
        }

        // حساب موقوف → إخراجه فوراً حتى لو كانت جلسته مفتوحة
        if (! Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'هذا الحساب موقوف.');
        }

        return $next($request);
    }
}
