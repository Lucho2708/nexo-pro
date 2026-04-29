<?php

namespace Database\Factories;

use App\Models\Asamblea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntervencionFactory extends Factory
{
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
