<?php

namespace Database\Factories;

use App\Modules\IAM\Models\LegalDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LegalDocument>
 */
class LegalDocumentFactory extends Factory
{
    protected $model = LegalDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['terms', 'privacy']),
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraphs(3, true),
            'version' => 1,
            'is_active' => true,
        ];
    }
}
