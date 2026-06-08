<?php

namespace App\Modules\Asamblea\Database\Factories;

use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Models\Intervencion;
use App\Modules\IAM\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntervencionFactory extends Factory
{
    protected $model = Intervencion::class;

    public function definition(): array
    {
        return [
            'asamblea_id' => Asamblea::factory(),
            'user_id' => User::factory(),
            'status' => 'pending',
            'requested_at' => now(),
        ];
    }
}
