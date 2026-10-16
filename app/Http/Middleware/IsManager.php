<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsManager
{
    /**
     * صلاحيات حساسة (الإعدادات، الكوبونات، التقارير، الحذف)
     * مسموحة فقط لـ: SUPER_ADMIN, MANAGER — وليس STAFF
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check() || ! Auth::user()->isManager()) {
            abort(403, 'هذه الصفحة متاحة للمدير فقط.');
        }

        return $next($request);
    }
}
