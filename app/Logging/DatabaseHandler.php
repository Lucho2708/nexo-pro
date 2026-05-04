<?php

namespace App\Logging;

use App\Models\SystemLog;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Schema;

class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * Escribe el log en la base de datos de manera asíncrona (si se usa un job) o síncrona.
     */
    protected function write(LogRecord $record): void
    {
        try {
            // Evitar bucles infinitos y errores en transacciones abortadas (Postgres)
            if (!Schema::hasTable('system_logs')) {
                return;
            }

            SystemLog::create([
                'level_name' => $record->level->getName(),
                'level' => $record->level->value,
                'message' => $record->message,
                'context' => $record->context,
                'env' => config('app.env'),
                'copropiedad_id' => auth()->check() ? auth()->user()->current_copropiedad_id : null,
                'user_id' => auth()->check() ? auth()->id() : null,
            ]);
        } catch (\Throwable $e) {
            // Failsafe: Si la DB falla, la transacción está abortada o la tabla no existe, 
            // simplemente ignoramos el log en DB para no romper la aplicación principal.
        }
    }
}
