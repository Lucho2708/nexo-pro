<?php

namespace App\Modules\Property\Observers;

use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Services\CoeficienteCalculator;

class TipoUnidadObserver
{
    public function __construct(
        protected CoeficienteCalculator $calculator
    ) {}

    /**
     * Handle the TipoUnidad "saved" event.
     */
    public function saved(TipoUnidad $tipoUnidad): void
    {
        // Si el área cambió, recalcular los coeficientes de toda la copropiedad
        if ($tipoUnidad->wasChanged('area_m2') || $tipoUnidad->wasRecentlyCreated) {
            $copropiedad = $tipoUnidad->copropiedad;
            if ($copropiedad) {
                $this->calculator->calculateForCopropiedad($copropiedad);
            }
        }
    }
}
