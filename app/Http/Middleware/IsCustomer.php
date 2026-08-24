<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsCustomer
{
    /**
     * السماح لأي مستخدم مسجّل (عميل أو إدارة)
     * الهدف: منع الزوار غير المسجّلين فقط
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول للوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}
