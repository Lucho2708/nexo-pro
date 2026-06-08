<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Property\Controllers\Admin\CopropiedadController;
use App\Modules\Property\Controllers\Admin\UnidadController;
use App\Modules\Property\Controllers\Admin\TipoUnidadController;
use App\Modules\Property\Controllers\Admin\ZonaComunController;

Route::middleware(['auth', 'verified', 'ensure-legal', 'two-factor'])->group(function () {

    // ── ADMINISTRADOR (Gestión de Inmuebles) ──────────────────
    Route::middleware(['ensure-admin', 'check-license', 'standalone-gate'])->group(function () {
        
        // Gestión Zonas Comunes Admin
        Route::get('/admin/zonas', [ZonaComunController::class, 'index'])->name('admin.zonas.index');
        Route::post('/admin/zonas', [ZonaComunController::class, 'store'])->name('admin.zonas.store');
        Route::patch('/admin/zonas/{zona}', [ZonaComunController::class, 'update'])->name('admin.zonas.update');
        Route::patch('/admin/zonas/{zona}/toggle', [ZonaComunController::class, 'toggleStatus'])->name('admin.zonas.toggle');
    });

    // Unidades y Configuración
    Route::middleware(['ensure-admin', 'check-license'])->group(function () {
        // Unidades y Configuración
        Route::post('/admin/unidades/bulk', [UnidadController::class, 'bulkGenerate'])->name('unidades.bulk-generate');

        // Gestión de Copropiedades (Multi-tenant)
        Route::get('/admin/copropiedades/create', [CopropiedadController::class, 'create'])->name('admin.copropiedades.create');
        Route::post('/admin/copropiedades', [CopropiedadController::class, 'store'])->name('admin.copropiedades.store');
        
        // Configuración de la Copropiedad (Settings)
        Route::get('/admin/settings', [CopropiedadController::class, 'settings'])
            ->middleware('permission:property:manage')
            ->name('admin.settings');
        Route::patch('/admin/settings', [CopropiedadController::class, 'updateSettings'])->name('admin.settings.update');

        // Gestión de Tipos de Unidad (Hoja de Vida)
        Route::post('/admin/unit-types', [TipoUnidadController::class, 'store'])->name('admin.unit-types.store');
        Route::post('/admin/unit-types/lock', [TipoUnidadController::class, 'lock'])->name('admin.unit-types.lock');
        Route::post('/admin/unit-types/generate', [TipoUnidadController::class, 'generateUnits'])->name('admin.settings.generate_units');
        Route::post('/admin/unit-types/unlock', [TipoUnidadController::class, 'unlock'])->name('admin.unit-types.unlock');
    });
});
