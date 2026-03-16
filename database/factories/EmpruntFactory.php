<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Emprunt>
 */
class EmpruntFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    // On crée une date d'emprunt au hasard dans les 2 derniers mois
    $dateEmprunt = $this->faker->dateTimeBetween('-2 months', 'now');
    
    // On calcule la date de retour prévue (+30 jours comme dans le CDC)
    $dateRetourPrevue = (clone $dateEmprunt)->modify('+30 days');

    return [
        'date_emprunt' => $dateEmprunt,
        'date_retour_prevue' => $dateRetourPrevue,
        // On met une date de retour effectif au hasard (ou null si pas encore rendu)
        'date_retour_effectif' => $this->faker->optional(0.7)->dateTimeBetween($dateEmprunt, 'now'),
        'usager_id' => \App\Models\User::all()->random()->id,
        'exemplaire_id' => \App\Models\Exemplaire::all()->random()->id,
    ];
}
}
