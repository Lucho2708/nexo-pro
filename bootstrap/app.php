<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
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
            'log-feature'  => \App\Http\Middleware\LogFeatureUsage::class,
            'check-license'      => \App\Http\Middleware\CheckLicenseStatus::class,
            'two-factor'         => \App\Http\Middleware\EnsureTwoFactorIsConfigured::class,
            'ensure-asamblea'    => \App\Http\Middleware\EnsureAsambleaIsActive::class,
            'ensure-legal'       => \App\Http\Middleware\EnsureLegalConsent::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
