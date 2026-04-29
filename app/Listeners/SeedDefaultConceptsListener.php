<?php

namespace App\Listeners;

use App\Events\CopropiedadCreated;
use App\Models\ConceptoCobro;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SeedDefaultConceptsListener
{
    /**
     * Handle the event.
     */
    public function handle(CopropiedadCreated $event): void
    {
        $defaults = [
            ['nombre' => 'Cuota Administración', 'codigo' => 'ADM', 'descripcion' => 'Cuota ordinaria mensual'],
            ['nombre' => 'Intereses Mora', 'codigo' => 'INT', 'descripcion' => 'Recargo por pago tardío'],
            ['nombre' => 'Fondo Reserva', 'codigo' => 'RES', 'descripcion' => 'Aporte fondo legal'],
        ];

        foreach ($defaults as $concept) {
            ConceptoCobro::firstOrCreate(
                [
                    'copropiedad_id' => $event->copropiedad->id,
                    'codigo' => $concept['codigo']
                ],
                $concept
            );
        }
    }
}
