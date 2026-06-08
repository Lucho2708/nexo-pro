<?php

namespace App\Modules\Property\Database\Factories;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadFactory extends Factory
{
    protected $model = Unidad::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => 'Apto ' . rand(100, 999),
            'torre' => 'Torre ' . rand(1, 5),
            'piso' => rand(1, 20),
            'coeficiente' => 0,
            'propietario_nombre' => 'Propietario ' . rand(1, 100),
            'saldo_actual' => 0,
        ];
    }
}
