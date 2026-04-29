<?php

namespace Database\Factories;

use App\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class CopropiedadFactory extends Factory
{
    protected $model = Copropiedad::class;

    public function definition(): array
    {
        return [
            'nit' => '900' . rand(100000, 999999) . '-1',
            'nombre' => 'Copropiedad ' . rand(1, 999) . ' Residencial',
            'direccion' => 'Calle ' . rand(1, 200) . ' # ' . rand(1, 100) . '-' . rand(1, 100),
            'ciudad' => 'Bogotá',
            'plan' => 'pro',
            'settings' => Copropiedad::defaultSettings(),
            'unidades_totales' => 100,
            'torres' => 4,
        ];
    }
}
