<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exemplaire>
 */
class ExemplaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'mise_en_service' => $this->faker->date(),
        'livre_id' => \App\Models\Livre::factory(),
        'statut_id' => \App\Models\Statut::factory(),
    ];
}
}
