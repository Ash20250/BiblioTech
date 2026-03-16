<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Statut;
use App\Models\Livre;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- NETTOYAGE COMPATIBLE SQLITE ---
        // Désactive les contraintes pour pouvoir vider les tables
        DB::statement('PRAGMA foreign_keys = OFF;');

        // On vide les tables manuellement (plus fiable que truncate sur SQLite)
        Emprunt::query()->delete();
        Exemplaire::query()->delete();
        Livre::query()->delete();
        Statut::query()->delete();
        Categorie::query()->delete();
        Auteur::query()->delete();
        User::query()->delete();

        // Réactive les contraintes
        DB::statement('PRAGMA foreign_keys = ON;');

        // --- 1. CRÉATION DES STATUTS ---
        $statuts = ['Neuf', 'Excellent', 'Bon', 'Moyen', 'Abîmé'];
        foreach ($statuts as $s) {
            Statut::create(['statut' => $s]);
        }

        // --- 2. CRÉATION DES CATÉGORIES ---
        $categories = ['Littérature Classique', 'Science-Fiction', 'Bande Dessinée', 'Histoire de France', 'Gastronomie', 'Développement Web', 'Poésie', 'Policier'];
        foreach ($categories as $c) {
            Categorie::create(['nom' => $c]); 
        }

        // --- 3. CRÉATION DES AUTEURS ---
        $auteursCelebres = ['Victor Hugo', 'Émile Zola', 'Jules Verne', 'Marcel Proust', 'Albert Camus', 'Simone de Beauvoir'];
        foreach ($auteursCelebres as $nom) {
            Auteur::create(['nom' => $nom]);
        }
        Auteur::factory(44)->create(); 

        // --- 4. CRÉATION DES UTILISATEURS (Point 5 du CDC) ---
        // Ton compte Admin pour les tests
        User::create([
            'name' => 'Admin Biblio',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'bibliothecaire', 
        ]);

        // Création de 27 usagers (Total 28) avec le rôle usager
        User::factory(27)->create([
            'role' => 'usager'
        ]);

        // --- 5. CRÉATION DES LIVRES (Gros volume pour le catalogue) ---
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 400; $i++) {
            Livre::create([
                'titre' => $this->genererTitreFrancais($faker),
                'auteur_id' => Auteur::inRandomOrder()->first()->id,
                'categorie_id' => Categorie::inRandomOrder()->first()->id,
            ]);
        }

        // --- 6. CRÉATION DES EXEMPLAIRES (Point 5 du CDC) ---
        Exemplaire::factory(1200)->create();

        // --- 7. CRÉATION DES EMPRUNTS (Point 5 du CDC : 250 emprunts) ---
        // On appelle ton EmpruntSeeder qui contient la logique pour l'admin
        $this->call([
            EmpruntSeeder::class,
        ]);
    }

    /**
     * Générateur de titres crédibles pour le catalogue
     */
    private function genererTitreFrancais($faker) {
        $prefix = ['Le secret de', 'L\'histoire de', 'Les chroniques de', 'Le guide du', 'Voyage au coeur de', 'L\'ombre du'];
        $sujet = ['Paris', 'la programmation', 'l\'alchimiste', 'la solitude', 'destin', 'chevalier', 'printemps'];
        return $prefix[array_rand($prefix)] . ' ' . $sujet[array_rand($sujet)] . ' ' . $faker->word();
    }
}