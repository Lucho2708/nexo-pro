<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Finance\Controllers\PaymentController;

Route::middleware(['auth', 'verified', 'ensure-legal', 'two-factor'])->group(function () {
    // ── GESTIÓN DE PAGOS ONLINE (Multi-pasarela) ──────────────────
    Route::post('/payments/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');
});

// Webhook unificado para todas las pasarelas (Sin CSRF)
Route::post('/payments/webhook', [PaymentController::class, 'handleWebhook'])->name('payments.webhook');
