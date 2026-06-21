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
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- NETTOYAGE ---
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Emprunt::query()->delete();
        Exemplaire::query()->delete();
        Livre::query()->delete();
        Statut::query()->delete();
        Categorie::query()->delete();
        Auteur::query()->delete();
        User::query()->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // --- 1. DONNÉES DE BASE ---
        $statutsRefs = ['Neuf', 'Excellent', 'Bon', 'Moyen', 'Abîmé'];
        foreach ($statutsRefs as $s) { Statut::create(['statut' => $s]); }

        $catNames = ['Littérature Classique', 'Science-Fiction', 'Bande Dessinée', 'Histoire', 'Gastronomie', 'Développement Web', 'Poésie', 'Policier'];
        foreach ($catNames as $c) { Categorie::create(['nom' => $c]); }

        $nomsAuteurs = ['Victor Hugo', 'Émile Zola', 'Jules Verne', 'Marcel Proust', 'Albert Camus', 'Simone de Beauvoir', 'Françoise Sagan', 'Jean d\'Ormesson', 'Colette', 'Molière'];
        foreach ($nomsAuteurs as $nom) { Auteur::create(['nom' => $nom]); }
        Auteur::factory(40)->create(); 

        User::create(['name' => 'Admin Biblio', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'bibliothecaire']);

        // --- 2. LIVRES ---
        $titres = [
            'Les Misérables', 'Germinal', 'Le Tour du monde en 80 jours', 'L\'Étranger', 'Le Deuxième Sexe',
            'Bonjour Tristesse', 'Le Bourgeois Gentilhomme', 'Fondation', '1984', 'Le Meilleur des mondes',
            'Vingt mille lieues sous les mers', 'Voyage au centre de la Terre', 'La Peste', 'La Chute', 'Nana',
            'L\'Assommoir', 'Bel-Ami', 'Pierre et Jean', 'Candide', 'Zadig', 'Madame Bovary', 'Salammbô',
            'Le Père Goriot', 'Illusions perdues', 'Notre-Dame de Paris', 'Le Rouge et le Noir', 'La Chartreuse de Parme',
            'Manon Lescaut', 'La Princesse de Clèves', 'L\'Avare', 'Tartuffe', 'Don Juan', 'Les Fleurs du Mal',
            'Alcools', 'Capitale de la douleur', 'Le Horla', 'Une vie', 'Cyrano de Bergerac', 'Phèdre', 'Andromaque',
            'Le Cid', 'Horace', 'Cinna', 'Paul et Virginie', 'Atala', 'René', 'La Cousine Bette', 'Le Cousin Pons',
            'La Bête humaine', 'La Terre', 'L\'Argent', 'Le Docteur Pascal', 'La Joie de vivre', 'Au Bonheur des Dames',
            'Pot-Bouille', 'Une page d\'amour', 'La Curée', 'La Fortune des Rougon', 'Le Ventre de Paris',
            'La Conquête de Plassans', 'Son Excellence Eugène Rougon', 'Travail', 'Vérité', 'Fécondité', 'Lourdes',
            'Rome', 'Paris', 'Le Rêve', 'L\'Œuvre', 'La Faute de l\'abbé Mouret', 'Contes de la bécasse', 'Toine',
            'Miss Harriet', 'Monsieur Parent', 'Boule de suif', 'La Maison Tellier', 'Clair de lune', 'Les Sœurs Rondoli',
            'L\'Inutile Beauté', 'La Petite Roque', 'Fort comme la mort', 'Notre cœur', 'Mont-Oriol', 'La Main gauche',
            'Le Rosier de Madame Husson', 'Les Contes du jour et de la nuit', 'Mademoiselle Fifi', 'Le Père Milon',
            'L\'Héritage', 'Le Champ d\'oliviers', 'L\'Épreuve', 'La Veillée', 'Aurélien', 'Les Choses', 'La Modification',
            'La Route des Flandres', 'L\'Herbe', 'Le Palace', 'L\'Emploi du temps', 'La Nausée'
        ];

        $auteurs = Auteur::all();
        $categories = Categorie::all();

        foreach ($titres as $t) {
            Livre::create([
                'titre' => $t,
                'auteur_id' => $auteurs->random()->id,
                'categorie_id' => $categories->random()->id,
            ]);
        }

        // --- 3. EXEMPLAIRES ALÉATOIRES (1 à 3) ---
        $statuts = Statut::all();
        foreach (Livre::all() as $livre) {
            $nbExemplaires = rand(1, 3);
            for ($i = 0; $i < $nbExemplaires; $i++) {
                Exemplaire::create([
                    'livre_id' => $livre->id,
                    'statut_id' => $statuts->random()->id,
                    'mise_en_service' => Carbon::now()->subDays(rand(10, 100)),
                ]);
            }
        }
    } // Ceci ferme la fonction run()
} // Ceci ferme la classe DatabaseSeeder