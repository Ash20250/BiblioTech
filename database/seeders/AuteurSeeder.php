<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auteur;

class AuteurSeeder extends Seeder
{
    public function run(): void
    {
        $auteurs = [
            'William Gibson',
            'Robert C. Martin',
            'Cal Newport',
            'Andrew Hunt',
            'Moebius',
            'Alan Moore',
            'Isaac Asimov',
            'Frank Herbert',
            'Neal Stephenson',
            'Martin Fowler',
        ];

        foreach ($auteurs as $nom) {
            Auteur::query()->create([
                'nom' => $nom,
            ]);
        }
    }
}
