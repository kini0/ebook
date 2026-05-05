<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aliases utilisés dans les routes
        $middleware->alias([
            'admin'    => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);

        // CSRF — en L11, exclure ici (et non plus dans VerifyCsrfToken.php)
        $middleware->validateCsrfTokens(except: [
            'webhooks/payments/*',
        ]);

        // Trust proxies pour Laragon / reverse-proxy
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Exceptions\PaymentException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode(),
                ], 422);
            }
            return redirect()->route('checkout.failed')
                ->with('error', $e->getMessage());
        });
    })
    ->create();
