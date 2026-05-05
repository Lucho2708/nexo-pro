<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\CarteraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantSwitchController;
use Inertia\Inertia;

// ── PÁGINAS PÚBLICAS ──────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::post('/demo/request', [LandingController::class, 'requestDemo'])->name('demo.request');

// ── ACCESO A ASAMBLEA STANDALONE (Invitados) ─────────────────
Route::get('/asambleas/acceso/{asamblea}', [\App\Http\Controllers\AsambleaGuestController::class, 'showLogin'])->name('asambleas.guest.login');
Route::post('/asambleas/acceso/{asamblea}', [\App\Http\Controllers\AsambleaGuestController::class, 'login'])->name('asambleas.guest.attempt');

// ── PÁGINAS LEGALES (Habeas Data & SIC) ───────────────────────
Route::get('/legal/terminos-y-condiciones', function () {
    return Inertia::render('Legal/Terms');
})->name('legal.terminos');

Route::get('/legal/politica-privacidad', function () {
    return Inertia::render('Legal/Privacy');
})->name('legal.privacidad');

// // ── AUTENTICACIÓN (Comentado: Usando Fortify) ─────────────────────────────
// Route::middleware(['guest', 'throttle:login'])->group(function () {
//     Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('/login', [AuthenticatedSessionController::class, 'store']);
// });

// ── ONBOARDING (Registro de Clientes) ─────────────────────────
Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/register', [OnboardingController::class, 'index'])->name('register');
    Route::post('/register', [OnboardingController::class, 'store']);
});

// Telemetría de Frontend (Failsafe Reports) - Protegido por Sanctum/Auth según convenga, o abierto para atrapar todo
Route::post('/telemetry/frontend-error', [\App\Http\Controllers\TelemetryController::class, 'logFrontendError'])
    ->middleware('web') // Web middleware para que Auth::user() esté disponible en Monolog
    ->name('telemetry.frontend');

Route::middleware(['auth', 'verified', 'ensure-legal'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/notifications/{announcement}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');

    // ── CONFIGURACIÓN INICIAL 2FA ─────────────────────────────────
    Route::get('/setup-2fa', function () {
        return Inertia::render('Auth/SetupTwoFactor');
    })->name('two-factor.setup');

    Route::middleware('two-factor')->group(function () {
        // ── PERFIL GLOBAL (Común) ───────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── MULTI-TENANT SWITCH (Común) ─────────────────────────────────
    Route::post('/switch-tenant/{copropiedad}', TenantSwitchController::class)->name('tenant.switch');

    // ── GESTIÓN DE PQRS (Compartida Admin/Owner) ──────────────────
    Route::get('/pqrs', [\App\Http\Controllers\PqrsController::class, 'index'])->middleware(['check-license', 'log-feature:pqrs'])->name('pqrs.index');
    Route::post('/pqrs', [\App\Http\Controllers\PqrsController::class, 'store'])->name('pqrs.store');
    Route::get('/pqrs/{pqrs}/download', [\App\Http\Controllers\PqrsController::class, 'downloadPdf'])->name('pqrs.download');
    Route::patch('/pqrs/{pqrs}', [\App\Http\Controllers\PqrsController::class, 'update'])->name('pqrs.update');

    // ── SOPORTE TÉCNICO (Errores del Sistema) ──────────────────────
    Route::get('/soporte', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::post('/soporte', [\App\Http\Controllers\SupportController::class, 'store'])->name('support.store');

    // ── SUPER ADMINISTRADOR (Platform Analytics & Settings) ──────────────────────────
    Route::post('/super-admin/impersonate-leave', [\App\Http\Controllers\SuperAdmin\ImpersonationController::class, 'stop'])->name('superadmin.impersonate.stop');

    Route::middleware(['super-admin'])->group(function () {
        Route::get('/super-admin', function() {
            return redirect()->route('superadmin.dashboard');
        });
        
        Route::get('/super-admin/stats', [\App\Http\Controllers\SuperAdmin\CopropiedadController::class, 'index'])->name('superadmin.dashboard');
        Route::put('/super-admin/copropiedades/{copropiedad}', [\App\Http\Controllers\SuperAdmin\CopropiedadController::class, 'update'])->name('superadmin.copropiedades.update');
        Route::get('/super-admin/licenses', [\App\Http\Controllers\SuperAdmin\LicenseController::class, 'index'])->name('superadmin.licenses.index');
        Route::post('/super-admin/licenses/{copropiedad}', [\App\Http\Controllers\SuperAdmin\LicenseController::class, 'update'])->name('superadmin.licenses.update');
        Route::get('/super-admin/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index'])->name('superadmin.users.index');
        Route::patch('/super-admin/users/{user}/status', [\App\Http\Controllers\SuperAdmin\UserController::class, 'toggleStatus'])->name('superadmin.users.toggle-status');
        Route::post('/super-admin/users/{user}/reset-2fa', [\App\Http\Controllers\SuperAdmin\UserController::class, 'resetTwoFactor'])->name('superadmin.users.reset-2fa');
        Route::post('/super-admin/impersonate/{user}', [\App\Http\Controllers\SuperAdmin\ImpersonationController::class, 'start'])->name('superadmin.impersonate');
        Route::get('/super-admin/audit', [\App\Http\Controllers\SuperAdmin\AuditController::class, 'index'])->name('superadmin.audit');
        Route::get('/super-admin/audit/export', [\App\Http\Controllers\SuperAdmin\AuditController::class, 'export'])->name('superadmin.audit.export');
        
        // Gestión Avanzada de Copropiedades y Transferencia de Mando
        Route::get('/super-admin/properties/{copropiedad}/manage', [\App\Http\Controllers\SuperAdmin\CopropiedadManagementController::class, 'manage'])->name('superadmin.properties.manage');
        Route::post('/super-admin/properties/{copropiedad}/transfer', [\App\Http\Controllers\SuperAdmin\CopropiedadManagementController::class, 'transfer'])->name('superadmin.properties.transfer');
        
        // Visor de Logs del Sistema (Monitoreo Técnico)
        Route::get('/super-admin/logs', [\App\Http\Controllers\SuperAdmin\LogViewerController::class, 'index'])->name('superadmin.logs');
        Route::post('/super-admin/logs/purge', [\App\Http\Controllers\SuperAdmin\LogViewerController::class, 'purge'])->name('superadmin.logs.purge');
        
        Route::get('/super-admin/announcements', [\App\Http\Controllers\SuperAdmin\AnnouncementController::class, 'index'])->name('superadmin.announcements.index');
        Route::post('/super-admin/announcements', [\App\Http\Controllers\SuperAdmin\AnnouncementController::class, 'store'])->name('superadmin.announcements.store');
        Route::patch('/super-admin/announcements/{announcement}', [\App\Http\Controllers\SuperAdmin\AnnouncementController::class, 'update'])->name('superadmin.announcements.update');
        Route::delete('/super-admin/announcements/{announcement}', [\App\Http\Controllers\SuperAdmin\AnnouncementController::class, 'destroy'])->name('superadmin.announcements.destroy');

        // Configuración Global del Sistema
        Route::get('/super-admin/settings', [\App\Http\Controllers\SuperAdmin\SystemSettingsController::class, 'index'])->name('superadmin.settings.index');
        Route::patch('/super-admin/settings', [\App\Http\Controllers\SuperAdmin\SystemSettingsController::class, 'update'])->name('superadmin.settings.update');

        // Centro de Soporte Técnico (Mesa de Ayuda)
        Route::get('/super-admin/support', [\App\Http\Controllers\SuperAdmin\SupportController::class, 'index'])->name('superadmin.support.index');
        Route::patch('/super-admin/support/{ticket}', [\App\Http\Controllers\SuperAdmin\SupportController::class, 'update'])->name('superadmin.support.update');
        Route::delete('/super-admin/support/{ticket}', [\App\Http\Controllers\SuperAdmin\SupportController::class, 'destroy'])->name('superadmin.support.destroy');
    });

    // ── GESTIÓN LEGAL (Habeas Data) ────────────────────────────────
    Route::get('/legal/consent', [LegalController::class, 'showConsent'])->name('legal.consent');
    Route::post('/legal/accept/{document}', [LegalController::class, 'accept'])->name('legal.accept');

    Route::middleware(['super-admin'])->group(function () {
        Route::get('/super-admin/legal', [\App\Http\Controllers\SuperAdmin\LegalManagementController::class, 'index'])->name('superadmin.legal.index');
        Route::get('/super-admin/legal/create', [\App\Http\Controllers\SuperAdmin\LegalManagementController::class, 'create'])->name('superadmin.legal.create');
        Route::post('/super-admin/legal', [\App\Http\Controllers\SuperAdmin\LegalManagementController::class, 'store'])->name('superadmin.legal.store');
        Route::patch('/super-admin/legal/{document}/toggle', [\App\Http\Controllers\SuperAdmin\LegalManagementController::class, 'toggle'])->name('superadmin.legal.toggle');
    });

    // Dashboard central (redirecciona según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified', 'check-license', 'log-feature:dashboard'])
        ->name('dashboard');

    // ── ADMINISTRADOR (protegido + logging de uso) ──────────────────
    Route::middleware(['ensure-admin', 'check-license', 'standalone-gate'])->group(function () {
        Route::patch('/notifications/{notification}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');

        // Cartera y Recaudo
        Route::get('/cartera', [CarteraController::class, 'index'])->middleware('log-feature:cartera')->name('cartera.index');
        Route::post('/cartera/import', [CarteraController::class, 'import'])->name('cartera.import');
        Route::post('/cartera/payment', [CarteraController::class, 'storePayment'])->name('cartera.payment');
        Route::post('/cartera/trigger-billing', [CarteraController::class, 'triggerMonthlyBilling'])->name('cartera.billing.trigger');
        Route::get('/cartera/statement/{unidad}', [CarteraController::class, 'downloadAccountStatement'])->name('cartera.statement.download');

        // Gestión Zonas Comunes Admin
        Route::get('/admin/zonas', [\App\Http\Controllers\Admin\ZonaComunController::class, 'index'])->name('admin.zonas.index');
        Route::post('/admin/zonas', [\App\Http\Controllers\Admin\ZonaComunController::class, 'store'])->name('admin.zonas.store');
        Route::patch('/admin/zonas/{zona}', [\App\Http\Controllers\Admin\ZonaComunController::class, 'update'])->name('admin.zonas.update');
        Route::patch('/admin/zonas/{zona}/toggle', [\App\Http\Controllers\Admin\ZonaComunController::class, 'toggleStatus'])->name('admin.zonas.toggle');

        // Reservas Admin
        Route::get('/admin/reservas', [\App\Http\Controllers\ReservaController::class, 'adminIndex'])->middleware('log-feature:reservas')->name('admin.reservas.index');
        Route::patch('/admin/reservas/{id}/status', [\App\Http\Controllers\ReservaController::class, 'updateStatus'])->name('admin.reservas.status');
    });

    // Rutas de Asambleas (Abiertas para Standalone Admin)
    Route::middleware(['ensure-admin', 'check-license'])->group(function () {
        // Asambleas Admin
        Route::get('/admin/asambleas', [\App\Http\Controllers\Admin\AsambleaManagementController::class, 'index'])->name('admin.asambleas.index');
        Route::get('/admin/asambleas/standalone/create', [\App\Http\Controllers\Admin\StandaloneAsambleaController::class, 'create'])->name('admin.asambleas.standalone.create');
        Route::post('/admin/asambleas/standalone', [\App\Http\Controllers\Admin\StandaloneAsambleaController::class, 'store'])->name('admin.asambleas.standalone.store');
        Route::post('/admin/asambleas', [\App\Http\Controllers\Admin\AsambleaManagementController::class, 'store'])->name('admin.asambleas.store');
        Route::patch('/admin/asambleas/{asamblea}/toggle', [\App\Http\Controllers\Admin\AsambleaManagementController::class, 'toggle'])->name('admin.asambleas.toggle');
        Route::delete('/admin/asambleas/{asamblea}', [\App\Http\Controllers\Admin\AsambleaManagementController::class, 'destroy'])->name('admin.asambleas.destroy');
        Route::post('/admin/asambleas/coeficientes/recalculate', [\App\Http\Controllers\Admin\AsambleaManagementController::class, 'recalculateCoeficientes'])->name('admin.asambleas.coeficientes.recalculate');

        // Unidades y Configuración
        Route::post('/admin/unidades/bulk', [\App\Http\Controllers\Admin\UnidadController::class, 'bulkGenerate'])->name('unidades.bulk-generate');

        // Anuncios Locales
        Route::get('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements.index');
        Route::post('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::delete('/admin/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

        // Gestión de Copropiedades (Multi-tenant)
        Route::get('/admin/copropiedades/create', [\App\Http\Controllers\Admin\CopropiedadController::class, 'create'])->name('admin.copropiedades.create');
        Route::post('/admin/copropiedades', [\App\Http\Controllers\Admin\CopropiedadController::class, 'store'])->name('admin.copropiedades.store');
        
        // Configuración de la Copropiedad (Settings)
        Route::get('/admin/settings', [\App\Http\Controllers\Admin\CopropiedadController::class, 'settings'])->name('admin.settings');
        Route::patch('/admin/settings', [\App\Http\Controllers\Admin\CopropiedadController::class, 'updateSettings'])->name('admin.settings.update');

        // Gestión de Tipos de Unidad (Hoja de Vida)
        Route::post('/admin/unit-types', [\App\Http\Controllers\Admin\TipoUnidadController::class, 'store'])->name('admin.unit-types.store');
        Route::post('/admin/unit-types/lock', [\App\Http\Controllers\Admin\TipoUnidadController::class, 'lock'])->name('admin.unit-types.lock');
        Route::post('/admin/unit-types/generate', [\App\Http\Controllers\Admin\TipoUnidadController::class, 'generateUnits'])->name('admin.settings.generate_units');
        Route::post('/admin/unit-types/unlock', [\App\Http\Controllers\Admin\TipoUnidadController::class, 'unlock'])->name('admin.unit-types.unlock');
    });

    // ── PROPIETARIO (portal móvil-first) ────────────────────────────
    Route::middleware(['ensure-owner', 'check-license'])->group(function () {
        Route::get('/portal', [\App\Http\Controllers\Owner\OwnerPortalController::class, 'index'])->middleware('log-feature:dashboard')->name('owner.dashboard');
        Route::get('/portal/payments', [\App\Http\Controllers\Owner\OwnerPortalController::class, 'payments'])->middleware('log-feature:payments')->name('owner.payments');

        // Reservas del propietario
        Route::get('/reservas', [\App\Http\Controllers\ReservaController::class, 'index'])->middleware('log-feature:reservas')->name('reservas.index');
        Route::post('/reservas', [\App\Http\Controllers\ReservaController::class, 'store'])->name('reservas.store');
        Route::post('/reservas/{reserva}/cancel', [\App\Http\Controllers\ReservaController::class, 'cancel'])->name('reservas.cancel');

        // ── GESTIÓN DE PAGOS ONLINE (Wompi) ───────────────────────────
        Route::post('/payments/initiate', [\App\Http\Controllers\Payment\PaymentController::class, 'initiate'])->name('payments.initiate');
    });

    // ── ASAMBLEA VIRTUAL (Acceso a la sala) ─────────────────────────
    Route::middleware(['ensure-asamblea'])->group(function () {
        Route::get('/asambleas/{asamblea}', [\App\Http\Controllers\AsambleaController::class, 'show'])->name('asambleas.show');
        Route::post('/asambleas/{asamblea}/reset', [\App\Http\Controllers\AsambleaController::class, 'resetConnection'])->name('asambleas.reset-connection');
        Route::post('/asambleas/preguntas/{pregunta}/votar', [\App\Http\Controllers\AsambleaController::class, 'votar'])->name('asambleas.votar');
        Route::get('/asambleas/preguntas/{pregunta}/results', [\App\Http\Controllers\AsambleaController::class, 'results'])->name('asambleas.preguntas.results');
        
        // Intervenciones (Propietario)
        Route::post('/asambleas/{asamblea}/intervenciones/request', [\App\Http\Controllers\Asamblea\IntervencionController::class, 'request'])->name('asambleas.intervenciones.request');
        Route::post('/asambleas/intervenciones/{intervencion}/cancel', [\App\Http\Controllers\Asamblea\IntervencionController::class, 'cancel'])->name('asambleas.intervenciones.cancel');
        Route::post('/asambleas/intervenciones/{intervencion}/close', [\App\Http\Controllers\Asamblea\IntervencionController::class, 'close'])->name('asambleas.intervenciones.close');

        // Moderación (Solo Admin)
        Route::middleware(['ensure-admin'])->group(function () {
            Route::post('/asambleas/intervenciones/{intervencion}/grant', [\App\Http\Controllers\Asamblea\IntervencionController::class, 'grant'])->name('asambleas.intervenciones.grant');
            Route::post('/asambleas/intervenciones/{intervencion}/extend', [\App\Http\Controllers\Asamblea\IntervencionController::class, 'extend'])->name('asambleas.intervenciones.extend');
            Route::get('/asambleas/{asamblea}/report', [\App\Http\Controllers\AsambleaController::class, 'report'])->name('asambleas.report');
            
            // Gestión de Preguntas (Moderación)
            Route::post('/asambleas/{asamblea}/preguntas', [\App\Http\Controllers\Asamblea\PreguntaController::class, 'store'])->name('asambleas.preguntas.store');
            Route::post('/asambleas/preguntas/{pregunta}/open', [\App\Http\Controllers\Asamblea\PreguntaController::class, 'open'])->name('asambleas.preguntas.open');
            Route::post('/asambleas/preguntas/{pregunta}/close', [\App\Http\Controllers\Asamblea\PreguntaController::class, 'close'])->name('asambleas.preguntas.close');
        });
    });
    });
});

// ── VISTA PREVIA DE CORREOS (Solo Desarrollo) ──────────────────
if (app()->environment('local')) {
    Route::get('/preview-mail/welcome', function () {
        $user = \App\Modules\IAM\Models\User::first() ?? \App\Modules\IAM\Models\User::factory()->make();
        $copropiedad = \App\Models\Copropiedad::first();
        return (new \App\Notifications\WelcomeAdminNotification($user, $copropiedad))
            ->toMail($user);
    });

    Route::get('/preview-mail/payment', function () {
        $user = \App\Modules\IAM\Models\User::first() ?? \App\Modules\IAM\Models\User::factory()->make();
        $transaccion = \App\Models\Transaccion::where('tipo', 'abono')->first();
        $unidad = \App\Models\Unidad::first();
        
        if (!$transaccion) return "Necesitas al menos una transacción de tipo 'abono' en la DB.";
        
        return (new \App\Notifications\PaymentRegisteredNotification($transaccion, $user, $unidad))
            ->toMail($user);
    });
}

// Webhook Wompi (Sin CSRF via bootstrap/app.php)
Route::post('/payments/webhook', [\App\Http\Controllers\Payment\PaymentController::class, 'handleWebhook'])->name('payments.webhook');

