<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\LeadCaptureController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes (Plataforma Base, Landing Pública y Servicios Compartidos)
|--------------------------------------------------------------------------
| Los módulos de entorno están desacoplados e independizados en sus
| respectivos archivos de ruta (routes/sysadmin.php, routes/backoffice.php,
| routes/admin.php, routes/portal.php, routes/porteria.php).
*/

// ── PÁGINAS PÚBLICAS Y CAPTACIÓN DE LEADS (PROPTECH ROI) ───────────
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/demo/request', [LeadCaptureController::class, 'store'])->name('demo.request');
Route::post('/leads', [LeadCaptureController::class, 'store'])->middleware('throttle:10,1')->name('leads.store');
Route::get('/leads/simulate', [LeadCaptureController::class, 'simulate'])->middleware('throttle:30,1')->name('leads.simulate');

// ── PÁGINAS LEGALES PÚBLICAS (Habeas Data & SIC) ───────────────────
Route::get('/legal/terminos-y-condiciones', function () {
    return Inertia::render('Legal/Terms');
})->name('legal.terminos');

Route::get('/legal/politica-privacidad', function () {
    return Inertia::render('Legal/Privacy');
})->name('legal.privacidad');

// ── TELEMETRÍA DE FRONTEND (FAILSAFE REPORTS) ──────────────────────
Route::post('/telemetry/frontend-error', [\App\Http\Controllers\TelemetryController::class, 'logFrontendError'])
    ->middleware('web')
    ->name('telemetry.frontend');

// ── RUTAS AUTENTICADAS BASE Y DISPATCHER ──────────────────────────
Route::middleware(['auth', 'verified', 'ensure-legal'])->group(function () {

    Route::post('/notifications/{announcement}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');

    // ── CONFIGURACIÓN INICIAL 2FA ─────────────────────────────────
    Route::get('/setup-2fa', function () {
        return Inertia::render('Auth/SetupTwoFactor');
    })->name('two-factor.setup');

    // ── GESTIÓN LEGAL (Consentimiento Habeas Data) ────────────────
    Route::get('/legal/consent', [LegalController::class, 'showConsent'])->name('legal.consent');
    Route::post('/legal/accept/{document}', [LegalController::class, 'accept'])->name('legal.accept');

    Route::middleware('two-factor')->group(function () {
        // ── GESTIÓN DE PQRS (Compartida Admin/Owner) ──────────────
        Route::get('/pqrs', function (\Illuminate\Http\Request $request) {
            if (auth()->user()?->role === 'admin') {
                return redirect()->route('admin.pqrs.index');
            }
            return app(\App\Http\Controllers\PqrsController::class)->index($request);
        })->middleware(['check-license', 'log-feature:pqrs'])->name('pqrs.index');
        Route::post('/pqrs', [\App\Http\Controllers\PqrsController::class, 'store'])->name('pqrs.store');
        Route::get('/pqrs/{pqrs}/download', [\App\Http\Controllers\PqrsController::class, 'downloadPdf'])->name('pqrs.download');
        Route::patch('/pqrs/{pqrs}', [\App\Http\Controllers\PqrsController::class, 'update'])->name('pqrs.update');

        // ── SOPORTE TÉCNICO (Errores del Sistema) ──────────────────
        Route::get('/soporte', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
        Route::post('/soporte', [\App\Http\Controllers\SupportController::class, 'store'])->name('support.store');

        // ── DISPATCHER CENTRAL DE ACCESO SEGÚN ROL ─────────────────
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware(['check-license', 'log-feature:dashboard'])
            ->name('dashboard');
    });
});

// ── VISTA PREVIA DE CORREOS (Solo Desarrollo) ──────────────────────
if (app()->environment('local')) {
    Route::get('/preview-mail/welcome', function () {
        $user = \App\Modules\IAM\Models\User::first() ?? \App\Modules\IAM\Models\User::factory()->make();
        $copropiedad = \App\Modules\Property\Models\Copropiedad::first();
        return (new \App\Notifications\WelcomeAdminNotification($user, $copropiedad))
            ->toMail($user);
    });

    Route::get('/preview-mail/payment', function () {
        $user = \App\Modules\IAM\Models\User::first() ?? \App\Modules\IAM\Models\User::factory()->make();
        $transaccion = \App\Modules\Finance\Models\Transaccion::where('tipo', 'abono')->first();
        $unidad = \App\Modules\Property\Models\Unidad::first();
        
        if (!$transaccion) return "Necesitas al menos una transacción de tipo 'abono' en la DB.";
        
        return (new \App\Notifications\PaymentRegisteredNotification($transaccion, $user, $unidad))
            ->toMail($user);
    });
}

Route::get('/security/test-403', function () {
    abort(403);
})->name('security.test-403');
