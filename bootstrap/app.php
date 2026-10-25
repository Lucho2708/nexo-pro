<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(function () {
                require base_path('routes/sysadmin.php');
                require base_path('routes/backoffice.php');
                require base_path('routes/admin.php');
                require base_path('routes/portal.php');
                require base_path('routes/porteria.php');
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            (env('APP_ENV') === 'testing' || (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'testing')) ? '*' : '',
            'payments/webhook',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\CaptureSystemMetrics::class,
        ]);

        $middleware->alias([
            'super-admin'  => \App\Http\Middleware\EnsureSuperAdmin::class,
            'ensure-admin' => \App\Http\Middleware\EnsureAdmin::class,
            'ensure-owner' => \App\Http\Middleware\EnsureOwner::class,
            'ensure-security' => \App\Http\Middleware\EnsureSecurity::class,
            'ensure-backoffice' => \App\Http\Middleware\EnsureBackoffice::class,
            'log-feature'  => \App\Http\Middleware\LogFeatureUsage::class,
            'check-license'      => \App\Http\Middleware\CheckLicenseStatus::class,
            'two-factor'         => \App\Http\Middleware\EnsureTwoFactorIsConfigured::class,
            'ensure-asamblea'    => \App\Http\Middleware\EnsureAsambleaIsActive::class,
            'ensure-legal'       => \App\Http\Middleware\EnsureLegalConsent::class,
            'standalone-gate'    => \App\Http\Middleware\RedirectStandaloneAdmin::class,
            'permission'         => \App\Modules\IAM\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->header('X-Inertia')) {
                return \Inertia\Inertia::render('Errors/Forbidden', [
                    'message' => $e->getMessage() ?: 'No tienes los permisos necesarios para acceder a este módulo.'
                ])->toResponse($request)->setStatusCode(403);
            }
            return response()->view('errors.403', [
                'message' => $e->getMessage() ?: 'No tienes los permisos necesarios para acceder a este módulo.'
            ], 403);
        });
    })->create();
