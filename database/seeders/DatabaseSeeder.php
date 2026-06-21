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
        // --- NETTOYAGE COMPATIBLE MYSQL ---
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Emprunt::query()->delete();
        Exemplaire::query()->delete();
        Livre::query()->delete();
        Statut::query()->delete();
        Categorie::query()->delete();
        Auteur::query()->delete();
        User::query()->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // --- 1. CRÉATION DES STATUTS ---
        $statutsRefs = ['Neuf', 'Excellent', 'Bon', 'Moyen', 'Abîmé'];
        foreach ($statutsRefs as $s) {
            Statut::create(['statut' => $s]);
        }

        // --- 2. CRÉATION DES CATÉGORIES ---
        $catNames = ['Littérature Classique', 'Science-Fiction', 'Bande Dessinée', 'Histoire', 'Gastronomie', 'Développement Web', 'Poésie', 'Policier'];
        foreach ($catNames as $c) {
            Categorie::create(['nom' => $c]); 
        }

        // --- 3. CRÉATION DES AUTEURS (NETTOYÉ) ---
        $nomsAuteurs = ['Victor Hugo', 'Émile Zola', 'Jules Verne', 'Marcel Proust', 'Albert Camus', 'Simone de Beauvoir', 'Françoise Sagan', 'Jean d\'Ormesson', 'Colette', 'Molière'];
        foreach ($nomsAuteurs as $nom) {
            Auteur::create(['nom' => $nom]);
        }

        // Utilisation de la Factory pour créer 40 VRAIS noms au lieu des "Anonymes"
        Auteur::factory(40)->create();

        // --- 4. CRÉATION DES UTILISATEURS ---
        User::create([
            'name' => 'Admin Biblio',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'bibliothecaire', 
        ]);

        $nomsRealistes = [
            'Jean Dupont', 'Marie Curie', 'Lucas Bernard', 'Chloé Petit', 'Hugo Roux', 
            'Léa Fontaine', 'Nathan Muller', 'Emma Lefebvre', 'Enzo Morel', 'Manon Andre',
            'Théo Guerin', 'Jade Pasquier', 'Louis Boucher', 'Camille Gaillard', 'Simon Denis',
            'Alice Hubert', 'Arthur Brunet', 'Sarah Barthelemy', 'Thomas Rey', 'Clara Moulin'
        ];

        foreach ($nomsRealistes as $index => $nom) {
            User::create([
                'name' => $nom,
                'email' => "user" . ($index + 1) . "@test.com",
                'password' => Hash::make('password'),
                'role' => 'usager',
            ]);
        }
        
// --- 5. GÉNÉRATEUR DE TITRES UNIQUES ---
        $sujets = ['Laravel', 'Docker', 'React', 'Python', 'Intelligence', 'Robot', 'Lune', 'Soleil', 'Ocean', 'Montagne', 'Forêt', 'Code', 'Algorithme', 'Cyber', 'Data', 'Web', 'Mobile', 'Nuage', 'Système', 'Réseau'];
        $noms = ['Secret', 'Mystère', 'Enquête', 'Destin', 'Voyage', 'Ombre', 'Lumière', 'Silence', 'Cri', 'Appel', 'Rêve', 'Réalité', 'Passé', 'Futur', 'Monde', 'Univers', 'Atome', 'Énergie', 'Force', 'Esprit'];

        $allAuteurs = Auteur::all();
        $allCats = Categorie::all();
        $titresGeneres = [];

        // Étape A : On génère toutes les combinaisons possibles à l'avance
        foreach ($sujets as $s) {
            foreach ($noms as $n) {
                $titresGeneres[] = "$n de $s";
                $titresGeneres[] = "$s : $n";
            }
        }
        
        // Étape B : On élimine les doublons potentiels de la liste et on mélange
        $titresGeneres = array_unique($titresGeneres);
        shuffle($titresGeneres);

        // Étape C : On s'assure de ne pas demander plus de titres que ce qu'on a de dispo
        $totalLivresA_Creer = min(400, count($titresGeneres));

        for ($i = 0; $i < $totalLivresA_Creer; $i++) {
            Livre::create([
                // array_shift retire le premier titre de la liste : il ne pourra plus JAMAIS être réutilisé
                'titre' => array_shift($titresGeneres), 
                'auteur_id' => $allAuteurs->random()->id,
                'categorie_id' => $allCats->random()->id,
            ]);
        }

        // --- 6. CRÉATION DE 1000 EXEMPLAIRES ---
        $livres = Livre::all();
        $statuts = Statut::all();
        for ($k = 0; $k < 1000; $k++) {
            Exemplaire::create([
                'livre_id' => $livres->random()->id,
                'statut_id' => $statuts->random()->id,
                'mise_en_service' => Carbon::now()->subDays(rand(100, 1000)),
            ]);
        }

        // --- 7. CRÉATION DE 250 EMPRUNTS ---
        $usagers = User::where('role', 'usager')->get();
        $exemplaires = Exemplaire::all();

        for ($e = 0; $e < 250; $e++) {
            $dateEmprunt = Carbon::now()->subDays(rand(5, 60));
            $datePrevue = (clone $dateEmprunt)->addDays(30);
            
            $estRendu = rand(0, 1) === 1;
            $dateRetourEffectif = $estRendu ? (clone $dateEmprunt)->addDays(rand(1, 35)) : null;

            Emprunt::create([
                'usager_id' => $usagers->random()->id,
                'exemplaire_id' => $exemplaires->random()->id,
                'date_emprunt' => $dateEmprunt,
                'date_retour_prevue' => $datePrevue,
                'date_retour_effectif' => $dateRetourEffectif,
            ]);
        }
    }
}