<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Porteria\PorteriaController;

/*
|--------------------------------------------------------------------------
| Portería / Garita Environment Routes (Conjunto: Seguridad Operativa)
|--------------------------------------------------------------------------
| Módulo táctico offline-first y tiempo real para control de accesos peatonales,
| paquetería de encomiendas, recibos públicos, parqueaderos y minuta digital.
*/

Route::middleware([
    'auth',
    'verified',
    'ensure-legal',
    'two-factor',
    'ensure-security',
    'check-license',
])->prefix('porteria')->name('porteria.')->group(function () {
    Route::get('/', [PorteriaController::class, 'dashboard'])->name('dashboard');

    // Control de Visitas Peatonales y Vehiculares
    Route::get('/visitas', [PorteriaController::class, 'visitas'])->name('visitas.index');
    Route::post('/visitas', [PorteriaController::class, 'registrarIngreso'])->name('visitas.store');
    Route::patch('/visitas/{visita}/salida', [PorteriaController::class, 'registrarSalida'])->name('visitas.salida');

    // Gestión y Notificación de Recibos Públicos
    Route::get('/recibos', [PorteriaController::class, 'recibos'])->name('recibos.index');
    Route::post('/recibos', [PorteriaController::class, 'storeRecibo'])->name('recibos.store');

    // Correspondencia y Paquetería
    Route::get('/paquetes', [PorteriaController::class, 'paquetes'])->name('paquetes.index');
    Route::post('/paquetes', [PorteriaController::class, 'storePaquete'])->name('paquetes.store');
    Route::patch('/paquetes/{paquete}/entregar', [PorteriaController::class, 'entregarPaquete'])->name('paquetes.entregar');

    // Capacidad y Censo de Parqueaderos
    Route::get('/parqueaderos', [PorteriaController::class, 'parqueaderos'])->name('parqueaderos.index');
    Route::post('/parqueaderos', [PorteriaController::class, 'storeParqueadero'])->name('parqueaderos.store');
    Route::patch('/parqueaderos/{registro}/salida', [PorteriaController::class, 'salidaParqueadero'])->name('parqueaderos.salida');

    // Minuta Digital de Novedades y Relevo de Guardia
    Route::get('/minuta', [PorteriaController::class, 'minuta'])->name('minuta.index');
    Route::post('/minuta', [PorteriaController::class, 'storeMinuta'])->name('minuta.store');
});
