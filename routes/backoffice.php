<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\BackofficeLeadController;

/*
|--------------------------------------------------------------------------
| Backoffice Environment Routes (CRM Comercial & Expansión)
|--------------------------------------------------------------------------
| Rutas exclusivas para el equipo comercial, gestión de prospectos de conjuntos,
| scoring de leads PropTech y pipeline de conversión.
*/

Route::middleware([
    'auth',
    'verified',
    'ensure-legal',
    'two-factor',
    'ensure-backoffice',
])->prefix('backoffice')->name('backoffice.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('backoffice.leads.index');
    });

    Route::get('/leads', [BackofficeLeadController::class, 'index'])->name('leads.index');
    Route::patch('/leads/{lead}/estado', [BackofficeLeadController::class, 'updateEstado'])->name('leads.update-estado');
});
