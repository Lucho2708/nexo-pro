<?php

namespace Database\Seeders;

use App\Modules\Property\Models\ComponenteUnidad;
use Illuminate\Database\Seeder;

class ComponenteUnidadSeeder extends Seeder
{
    public function run(): void
    {
        $componentes = [
            'Habitación',
            'Baño',
            'Cocina',
            'Sala',
            'Comedor',
            'Balcón',
            'Terraza',
            'Estudio',
            'Zona de Ropas',
            'Patio',
            'Parqueadero',
            'Depósito',
        ];

        foreach ($componentes as $nombre) {
            ComponenteUnidad::firstOrCreate([
                'nombre' => $nombre,
            ]);
        }
    }
}
