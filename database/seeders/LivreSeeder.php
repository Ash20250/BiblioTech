<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Support\Str;

class LivreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Liste de livres "Originaux" et Tech (Fini 1984 et Le Petit Prince !)
        $vraisLivres = [
            // Développement & Tech
            ['titre' => 'The Pragmatic Programmer', 'auteur' => 'Andrew Hunt', 'cat' => 'Développement Web'],
            ['titre' => 'Refactoring', 'auteur' => 'Martin Fowler', 'cat' => 'Développement Web'],
            ['titre' => "Don't Make Me Think", 'auteur' => 'Steve Krug', 'cat' => 'Développement Web'],
            ['titre' => "Clean Architecture", 'auteur' => 'Robert C. Martin', 'cat' => 'Développement Web'],
            ['titre' => "Design Patterns", 'auteur' => 'Erich Gamma', 'cat' => 'Développement Web'],

            // Science-Fiction & Culture Geek (Plus original que Dune/1984)
            ['titre' => 'Le Guide du voyageur galactique', 'auteur' => 'Douglas Adams', 'cat' => 'Science-Fiction'],
            ['titre' => 'Neuromancien', 'auteur' => 'William Gibson', 'cat' => 'Science-Fiction'],
            ['titre' => 'Ready Player One', 'auteur' => 'Ernest Cline', 'cat' => 'Science-Fiction'],
            ['titre' => 'Snow Crash (Le Samouraï Virtuel)', 'auteur' => 'Neal Stephenson', 'cat' => 'Science-Fiction'],

            // BD & Romans Graphiques
            ['titre' => "L'Incal", 'auteur' => 'Moebius', 'cat' => 'Bande Dessinée'],
            ['titre' => "Saga", 'auteur' => 'Brian K. Vaughan', 'cat' => 'Bande Dessinée'],
            ['titre' => "Watchmen", 'auteur' => 'Alan Moore', 'cat' => 'Bande Dessinée'],

            // Management & Carrière
            ['titre' => "L'Art de la Victoire (Nike)", 'auteur' => 'Phil Knight', 'cat' => 'Management'],
            ['titre' => "Deep Work", 'auteur' => 'Cal Newport', 'cat' => 'Management'],
            ['titre' => "Soft Skills", 'auteur' => 'John Sonmez', 'cat' => 'Management'],
        ];

        foreach ($vraisLivres as $data) {
            $auteur = Auteur::firstOrCreate(['nom' => $data['auteur']]);
            $categorie = Categorie::firstOrCreate(['nom' => $data['cat']]);

            Livre::create([
                'titre' => $data['titre'],
                'auteur_id' => $auteur->id,
                'categorie_id' => $categorie->id,
            ]);
        }

        // 2. Génération automatique PROPRE (380 livres)
        $faker = \Faker\Factory::create('fr_FR');
        $categories = Categorie::all();
        $auteurs = Auteur::all();

        for ($i = 0; $i < 380; $i++) {
            // AU LIEU DE realText (qui fait des phrases bizarres)
            // ON UTILISE sentence() qui crée des suites de mots plus crédibles
            $titre = $faker->unique()->sentence(rand(2, 4));
            $titre = Str::title(str_replace('.', '', $titre)); // On nettoie le point final

            Livre::create([
                'titre' => $titre,
                'auteur_id' => $auteurs->random()->id,
                'categorie_id' => $categories->random()->id,
            ]);
        }
    }
}