<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\CopropiedadController;
use App\Http\Controllers\SuperAdmin\LicenseController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ImpersonationController;
use App\Http\Controllers\SuperAdmin\AuditController;
use App\Http\Controllers\SuperAdmin\CopropiedadManagementController;
use App\Http\Controllers\SuperAdmin\LogViewerController;
use App\Http\Controllers\SuperAdmin\AnnouncementController;
use App\Http\Controllers\SuperAdmin\SystemSettingsController;
use App\Http\Controllers\SuperAdmin\SupportController;
use App\Http\Controllers\SuperAdmin\LegalManagementController;

/*
|--------------------------------------------------------------------------
| SuperAdmin Environment Routes (Super-Admin / Plataforma)
|--------------------------------------------------------------------------
| Rutas exclusivas para la gestión global de instancias, copropiedades,
| auditoría general, licencias SaaS, impersonación y telemetría de plataforma.
*/

Route::middleware([
    'auth',
    'verified',
    'ensure-legal',
    'two-factor',
])->group(function () {

    Route::post('/super-admin/impersonate-leave', [ImpersonationController::class, 'stop'])
        ->name('superadmin.impersonate.stop');

    Route::middleware(['super-admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('superadmin.dashboard');
        });

        Route::get('/stats', [CopropiedadController::class, 'index'])->name('dashboard');
        Route::put('/copropiedades/{copropiedad}', [CopropiedadController::class, 'update'])->name('copropiedades.update');
        Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
        Route::post('/licenses/{copropiedad}', [LicenseController::class, 'update'])->name('licenses.update');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-2fa', [UserController::class, 'resetTwoFactor'])->name('users.reset-2fa');
        Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate');
        Route::get('/audit', [AuditController::class, 'index'])->name('audit');
        Route::get('/audit/export', [AuditController::class, 'export'])->name('audit.export');

        // Gestión Avanzada de Copropiedades y Transferencia de Mando
        Route::get('/properties/{copropiedad}/manage', [CopropiedadManagementController::class, 'manage'])->name('properties.manage');
        Route::post('/properties/{copropiedad}/transfer', [CopropiedadManagementController::class, 'transfer'])->name('properties.transfer');

        // Visor de Logs del Sistema (Monitoreo Técnico)
        Route::get('/logs', [LogViewerController::class, 'index'])->name('logs');
        Route::post('/logs/purge', [LogViewerController::class, 'purge'])->name('logs.purge');

        // Anuncios Globales de Plataforma
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Configuración Global del Sistema
        Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');

        // Centro de Soporte Técnico (Mesa de Ayuda)
        Route::get('/support', [SupportController::class, 'index'])->name('support.index');
        Route::patch('/support/{ticket}', [SupportController::class, 'update'])->name('support.update');
        Route::delete('/support/{ticket}', [SupportController::class, 'destroy'])->name('support.destroy');

        // Gestión Legal Centralizada (Habeas Data & SIC)
        Route::get('/legal', [LegalManagementController::class, 'index'])->name('legal.index');
        Route::get('/legal/create', [LegalManagementController::class, 'create'])->name('legal.create');
        Route::post('/legal', [LegalManagementController::class, 'store'])->name('legal.store');
        Route::patch('/legal/{document}/toggle', [LegalManagementController::class, 'toggle'])->name('legal.toggle');
    });
});
