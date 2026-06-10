<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Développement Web',
            'Science-Fiction',
            'Management',
            'Science',
            'Philosophie',
            'Histoire',
            'Design',
        ];

        foreach ($categories as $nom) {
            Categorie::query()->create([
                'nom' => $nom,
            ]);
        }
    }
}
