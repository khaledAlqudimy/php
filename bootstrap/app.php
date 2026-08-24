<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ─── تسجيل Middleware مخصص ───
        $middleware->alias([
            'admin'    => \App\Http\Middleware\IsAdmin::class,
            'customer' => \App\Http\Middleware\IsCustomer::class,
        ]);

        // ─── حماية CSRF لجميع POST/PUT/DELETE ───
        $middleware->validateCsrfTokens(except: [
            // أضف هنا أي مسار يحتاج إعفاء من CSRF (webhook مثلاً)
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ─── صفحات خطأ مخصصة ───
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return redirect()->route('login');
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $status = $e->getStatusCode();
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }
        });

    })->create();
