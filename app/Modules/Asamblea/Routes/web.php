<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Asamblea\Controllers\AsambleaController;
use App\Modules\Asamblea\Controllers\AsambleaGuestController;
use App\Modules\Asamblea\Controllers\Admin\AsambleaManagementController;
use App\Modules\Asamblea\Controllers\Admin\StandaloneAsambleaController;
use App\Modules\Asamblea\Controllers\Participante\IntervencionController;
use App\Modules\Asamblea\Controllers\Participante\PreguntaController;

// ── ACCESO A ASAMBLEA STANDALONE (Invitados) ─────────────────
Route::middleware('web')->group(function () {
    Route::get('/asambleas/acceso/{asamblea}', [AsambleaGuestController::class, 'showLogin'])->name('asambleas.guest.login');
    Route::post('/asambleas/acceso/{asamblea}', [AsambleaGuestController::class, 'login'])->name('asambleas.guest.attempt');
});

Route::middleware(['auth', 'verified', 'ensure-legal', 'two-factor', 'ensure-asamblea'])->group(function () {
    
    // Rutas de Asambleas (Admin)
    Route::middleware(['ensure-admin', 'check-license'])->group(function () {
        Route::get('/admin/asambleas', [AsambleaManagementController::class, 'index'])->name('admin.asambleas.index');
        Route::get('/admin/asambleas/standalone/create', [StandaloneAsambleaController::class, 'create'])->name('admin.asambleas.standalone.create');
        Route::post('/admin/asambleas/standalone', [StandaloneAsambleaController::class, 'store'])->name('admin.asambleas.standalone.store');
        Route::post('/admin/asambleas', [AsambleaManagementController::class, 'store'])->name('admin.asambleas.store');
        Route::patch('/admin/asambleas/{asamblea}/toggle', [AsambleaManagementController::class, 'toggle'])->name('admin.asambleas.toggle');
        Route::post('/admin/asambleas/voters/import', [AsambleaManagementController::class, 'importVoters'])->name('admin.asambleas.voters.import');
        Route::delete('/admin/asambleas/{asamblea}', [AsambleaManagementController::class, 'destroy'])->name('admin.asambleas.destroy');
        Route::post('/admin/asambleas/coeficientes/recalculate', [AsambleaManagementController::class, 'recalculateCoeficientes'])->name('admin.asambleas.coeficientes.recalculate');
    });

    // ── ASAMBLEA VIRTUAL (Acceso a la sala) ─────────────────────────
    Route::get('/asambleas/{asamblea}', [AsambleaController::class, 'show'])->name('asambleas.show');
    Route::post('/asambleas/{asamblea}/reset', [AsambleaController::class, 'resetConnection'])->name('asambleas.reset-connection');
    Route::post('/asambleas/preguntas/{pregunta}/votar', [AsambleaController::class, 'votar'])->name('asambleas.votar');
    Route::get('/asambleas/preguntas/{pregunta}/results', [AsambleaController::class, 'results'])->name('asambleas.preguntas.results');
    
    // Intervenciones (Propietario)
    Route::post('/asambleas/{asamblea}/intervenciones/request', [IntervencionController::class, 'request'])->name('asambleas.intervenciones.request');
    Route::post('/asambleas/intervenciones/{intervencion}/cancel', [IntervencionController::class, 'cancel'])->name('asambleas.intervenciones.cancel');
    Route::post('/asambleas/intervenciones/{intervencion}/close', [IntervencionController::class, 'close'])->name('asambleas.intervenciones.close');

    // Moderación (Solo Admin)
    Route::middleware(['ensure-admin'])->group(function () {
        Route::post('/asambleas/intervenciones/{intervencion}/grant', [IntervencionController::class, 'grant'])->name('asambleas.intervenciones.grant');
        Route::post('/asambleas/intervenciones/{intervencion}/extend', [IntervencionController::class, 'extend'])->name('asambleas.intervenciones.extend');
        Route::get('/asambleas/{asamblea}/report', [AsambleaController::class, 'report'])->name('asambleas.report');
        
        // Gestión de Preguntas (Moderación)
        Route::post('/asambleas/{asamblea}/preguntas', [PreguntaController::class, 'store'])->name('asambleas.preguntas.store');
        Route::post('/asambleas/preguntas/{pregunta}/open', [PreguntaController::class, 'open'])->name('asambleas.preguntas.open');
        Route::post('/asambleas/preguntas/{pregunta}/close', [PreguntaController::class, 'close'])->name('asambleas.preguntas.close');
    });
});
