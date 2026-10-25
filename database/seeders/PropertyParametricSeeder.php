<?php

namespace Database\Seeders;

use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Models\ComponenteUnidad;
use Illuminate\Database\Seeder;

class PropertyParametricSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Apartamento'],
            ['nombre' => 'Casa'],
            ['nombre' => 'Local Comercial'],
            ['nombre' => 'Oficina'],
        ];

        foreach ($tipos as $tipo) {
            TipoUnidad::updateOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        $componentes = [
            ['nombre' => 'Balcón'],
            ['nombre' => 'Terraza'],
            ['nombre' => 'Depósito'],
            ['nombre' => 'Parqueadero'],
        ];

        foreach ($componentes as $componente) {
            ComponenteUnidad::updateOrCreate(['nombre' => $componente['nombre']], $componente);
        }
    }
}
