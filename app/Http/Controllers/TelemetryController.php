<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelemetryController extends Controller
{
    /**
     * Recibe errores silenciosos desde el Failsafe del Frontend
     * y los canaliza hacia el Logger del Sistema central (Base de datos).
     */
    public function logFrontendError(Request $request)
    {
        // Validamos mínimamente para evitar abuso
        $request->validate([
            'message' => 'required|string|max:500',
            'context' => 'nullable|array',
            'level'   => 'nullable|string|in:info,warning,error,critical',
        ]);

        $message = '[Frontend Telemetry] ' . $request->input('message');
        $context = $request->input('context', []);
        $level = $request->input('level', 'error');

        // Canalizamos al Facade nativo de Laravel, el cual será interceptado
        // por nuestro DatabaseHandler y mandará esto directo a la tabla system_logs.
        Log::log($level, $message, $context);

        return response()->json(['status' => 'logged'], 201);
    }
}
