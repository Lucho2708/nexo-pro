<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'Parqueadero Privado',
            'Depósito',
        ];

        foreach ($componentes as $nombre) {
            DB::table('componentes_unidad')->insertOrIgnore([
                'id' => Str::uuid(),
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
