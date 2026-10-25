<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CarteraController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Admin Environment Routes (Conjunto: Administrador Operativo)
|--------------------------------------------------------------------------
| Rutas exclusivas para la administración operativa del conjunto residencial.
| Protegidas por autenticación, consentimiento legal, 2FA, verificación de rol
| administrador, validez de licencia y gate standalone.
*/

Route::middleware([
    'auth',
    'verified',
    'ensure-legal',
    'two-factor',
    'ensure-admin',
    'check-license',
    'standalone-gate',
])->group(function () {

    // ── DASHBOARD CANÓNICO DE ADMINISTRACIÓN ───────────────────────
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    // ── NOTIFICACIONES OPERATIVAS ──────────────────────────────────
    Route::patch('/admin/notifications/{notification}/read', [DashboardController::class, 'markNotificationAsRead'])->name('admin.notifications.read');
    Route::patch('/notifications/{notification}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');

    // ── GESTIÓN DE CARTERA Y RECAUDO ──────────────────────────────
    Route::prefix('admin/cartera')->name('admin.cartera.')->group(function () {
        Route::get('/', [CarteraController::class, 'index'])->middleware('log-feature:cartera')->name('index');
        Route::post('/import', [CarteraController::class, 'import'])->name('import');
        Route::post('/payment', [CarteraController::class, 'storePayment'])->name('payment');
        Route::post('/trigger-billing', [CarteraController::class, 'triggerMonthlyBilling'])->name('billing.trigger');
        Route::get('/statement/{unidad}', [CarteraController::class, 'downloadAccountStatement'])->name('statement.download');
    });

    // Alias retrocompatibles para Cartera (/cartera)
    Route::get('/cartera', [CarteraController::class, 'index'])->middleware('log-feature:cartera')->name('cartera.index');
    Route::post('/cartera/import', [CarteraController::class, 'import'])->name('cartera.import');
    Route::post('/cartera/payment', [CarteraController::class, 'storePayment'])->name('cartera.payment');
    Route::post('/cartera/trigger-billing', [CarteraController::class, 'triggerMonthlyBilling'])->name('cartera.billing.trigger');
    Route::get('/cartera/statement/{unidad}', [CarteraController::class, 'downloadAccountStatement'])->name('cartera.statement.download');

    // ── RESERVAS DE ZONAS COMUNES (ADMIN) ─────────────────────────
    Route::get('/admin/reservas', [ReservaController::class, 'adminIndex'])->middleware('log-feature:reservas')->name('admin.reservas.index');
    Route::patch('/admin/reservas/{id}/status', [ReservaController::class, 'updateStatus'])->name('admin.reservas.status');

    // ── COMUNICACIÓN Y ANUNCIOS DEL CONJUNTO ──────────────────────
    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
});
