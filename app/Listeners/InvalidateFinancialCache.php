<?php

namespace App\Listeners;

use App\Events\PaymentRegistered;
use Illuminate\Support\Facades\Cache;

class InvalidateFinancialCache
{
    /**
     * Handle the event.
     */
    public function handle(PaymentRegistered $event): void
    {
        $copropiedadId = $event->transaccion->unidad->copropiedad_id;
        $date = $event->transaccion->fecha;
        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));

        // Eliminar llaves específicas de esta copropiedad
        Cache::forget("collected_{$copropiedadId}_{$month}_{$year}");
        Cache::forget("units_in_debt_{$copropiedadId}");
        Cache::forget("top_overdue_{$copropiedadId}_5");
    }
}
