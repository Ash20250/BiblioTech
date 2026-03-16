<?php

namespace Database\Factories;

use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivreFactory extends Factory
{
    public function definition(): array
    {
        // Tableaux pour générer des titres crédibles en français
        $prefix = [
            'Le secret de', 'L\'histoire de', 'Les chroniques de', 'Voyage au coeur de', 
            'Le guide du', 'L\'ombre de', 'Les mystères de', 'Le silence de', 
            'La quête de', 'L\'appel de', 'Le grimoire de', 'L\'énigme de'
        ];

        $themes = [
            'la programmation', 'Paris', 'la solitude', 'destin', 'chevalier', 
            'l\'alchimiste', 'printemps', 'l\'hiver', 'la justice', 'la liberté', 
            'l\'espace', 'la mer', 'la forêt', 'l\'informatique', 'la réussite'
        ];

        // On mélange un préfixe, un thème et un petit mot aléatoire pour la variété
        $titre = $this->faker->randomElement($prefix) . ' ' . 
                 $this->faker->randomElement($themes) . ' ' . 
                 $this->faker->word();

        return [
            // ucfirst pour mettre la première lettre en majuscule
            'titre' => ucfirst($titre), 
            
            // On utilise inRandomOrder()->first() pour la performance
            'auteur_id' => Auteur::inRandomOrder()->first()->id ?? Auteur::factory(),
            'categorie_id' => Categorie::inRandomOrder()->first()->id ?? Categorie::factory(),
        ];
    }
}