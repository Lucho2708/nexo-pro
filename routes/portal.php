<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\OwnerPortalController;
use App\Http\Controllers\ReservaController;

/*
|--------------------------------------------------------------------------
| Portal Environment Routes (Conjunto: Propietario / Residente)
|--------------------------------------------------------------------------
| Rutas móviles y web para copropietarios: estado de cuenta, pasarela de pagos,
| reservas de amenidades y solicitudes comunitarias.
*/

Route::middleware([
    'auth',
    'verified',
    'ensure-legal',
    'two-factor',
    'ensure-owner',
    'check-license',
])->group(function () {

    Route::prefix('portal')->name('owner.')->group(function () {
        Route::get('/', [OwnerPortalController::class, 'index'])->middleware('log-feature:dashboard')->name('dashboard');
        Route::get('/payments', [OwnerPortalController::class, 'payments'])->middleware('log-feature:payments')->name('payments');
    });

    // Acciones de Reservas del Propietario
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/{reserva}/cancel', [ReservaController::class, 'cancel'])->name('reservas.cancel');
});
