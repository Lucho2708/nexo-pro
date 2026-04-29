<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhooks de Pasarelas de Pago
Route::post('/payments/webhook/{provider}', function (Request $request, $provider) {
    // This will be handled by a controller later
    return response()->json(['status' => 'received']);
})->middleware('throttle:6,1')->name('api.payments.webhook');
