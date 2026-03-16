<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livre;

class LivreSeeder extends Seeder
{
    public function run(): void
    {
        $livres = [
            // 💻 INFORMATIQUE
            ['titre' => 'Laravel Up & Running', 'auteur' => 'Matt Stauffer', 'theme' => 'Informatique'],
            ['titre' => 'Clean Code', 'auteur' => 'Robert C. Martin', 'theme' => 'Informatique'],
            ['titre' => 'Refactoring', 'auteur' => 'Martin Fowler', 'theme' => 'Informatique'],
            
            // 📈 MANAGEMENT & PRODUCTIVITÉ
            ['titre' => 'La 25ème Heure', 'auteur' => 'Guillaume Declair', 'theme' => 'Management'],
            ['titre' => 'Deep Work', 'auteur' => 'Cal Newport', 'theme' => 'Management'],
            ['titre' => 'Atomic Habits', 'auteur' => 'James Clear', 'theme' => 'Management'],
            
            // 📖 ROMAN & LITTÉRATURE
            ['titre' => '1984', 'auteur' => 'George Orwell', 'theme' => 'Roman'],
            ['titre' => 'Le Petit Prince', 'auteur' => 'Antoine de Saint-Exupéry', 'theme' => 'Roman'],
            ['titre' => 'L\'Alchimiste', 'auteur' => 'Paulo Coelho', 'theme' => 'Roman'],
            
            // ⚖️ DROIT
            ['titre' => 'Code Civil 2026', 'auteur' => 'Dalloz', 'theme' => 'Droit'],
            ['titre' => 'Code du Travail 2026', 'auteur' => 'LexisNexis', 'theme' => 'Droit'],
            
            // 🚀 SCIENCE-FICTION
            ['titre' => 'Fondation', 'auteur' => 'Isaac Asimov', 'theme' => 'SF'],
            ['titre' => 'Dune', 'auteur' => 'Frank Herbert', 'theme' => 'SF'],
            ['titre' => 'Le Meilleur des Mondes', 'auteur' => 'Aldous Huxley', 'theme' => 'SF'],
            
            // 🎨 ART & BD
            ['titre' => 'Astérix : L\'Iris Blanc', 'auteur' => 'Fabcaro', 'theme' => 'BD'],
            ['titre' => 'L\'Arabe du futur', 'auteur' => 'Riad Sattouf', 'theme' => 'BD'],
            ['titre' => 'Histoire de l\'Art', 'auteur' => 'E.H. Gombrich', 'theme' => 'Art'],
            
            // 🧠 PSYCHOLOGIE & SCIENCES
            ['titre' => 'Système 1 / Système 2', 'auteur' => 'Daniel Kahneman', 'theme' => 'Sciences'],
            ['titre' => 'Sapiens', 'auteur' => 'Yuval Noah Harari', 'theme' => 'Sciences'],
            ['titre' => 'Une brève histoire du temps', 'auteur' => 'Stephen Hawking', 'theme' => 'Sciences'],
        ];

        foreach ($livres as $livre) {
            Livre::create(array_merge($livre, [
                'isbn' => fake()->unique()->isbn13(),
                'annee_publication' => rand(2015, 2025),
                'disponible' => true
            ]));
        }
    }
}