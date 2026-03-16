<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campus;

class SalarieFactory extends Factory
{
public function definition(): array
{
    return [
        'nom' => $this->faker->lastName(),
        'sexe' => $this->faker->randomElement(['M', 'F']),
        'age' => $this->faker->numberBetween(18, 65),
        'email' => $this->faker->unique()->safeEmail(),
        'poste' => $this->faker->jobTitle(),
        'ville' => $this->faker->city(),
        'remote' => $this->faker->boolean(50), 
        'campus_id' => Campus::inRandomOrder()->first()->id ?? 1,
    ];
}
}