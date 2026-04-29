<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'message' => fake()->paragraph(),
            'type' => fake()->randomElement(['info', 'warning', 'danger']),
            'target_role' => 'all',
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addWeek(),
            'copropiedad_id' => null,
            'user_id' => null,
        ];
    }
}
