<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emprunt;
use App\Models\User;
use App\Models\Exemplaire;
use Carbon\Carbon;

class EmpruntSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@test.com')->first();
        $autresUsagers = User::where('email', '!=', 'admin@test.com')->get();
        $exemplaires = Exemplaire::all();

        if ($exemplaires->isEmpty()) {
            $this->command->error("Erreur : Aucun exemplaire en base. Lancez d'abord le LivreSeeder !");
            return;
        }

        // 1. Tes 15 emprunts perso (Mélange total pour ton profil)
        for ($i = 0; $i < 15; $i++) {
            $this->creerEmpruntAleatoire($admin, $exemplaires);
        }

        // 2. Les 235 autres emprunts pour le Registre Global
        for ($i = 0; $i < 235; $i++) {
            $this->creerEmpruntAleatoire($autresUsagers->random(), $exemplaires);
        }
    }

    /**
     * Logique pour créer un emprunt avec un statut aléatoire mélangé
     */
    private function creerEmpruntAleatoire($user, $exemplaires)
    {
        $dateEmprunt = Carbon::now()->subDays(rand(5, 100));
        $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
        $dateRetourEffectif = null;

        // Tirage au sort du statut (1: Rendu, 2: Retard, 3: En cours)
        $statutAleatoire = rand(1, 3);

        if ($statutAleatoire === 1) {
            // RENDU : On met une date de retour effective
            $dateRetourEffectif = $dateEmprunt->copy()->addDays(rand(10, 25));
        } elseif ($statutAleatoire === 2) {
            // RETARD : On s'assure que la date prévue est passée (ex: emprunté il y a 40 jours)
            $dateEmprunt = Carbon::now()->subDays(rand(35, 60));
            $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
            $dateRetourEffectif = null;
        } else {
            // EN COURS : Emprunt récent, pas encore rendu
            $dateEmprunt = Carbon::now()->subDays(rand(1, 20));
            $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
            $dateRetourEffectif = null;
        }

        Emprunt::create([
            'usager_id' => $user->id,
            'exemplaire_id' => $exemplaires->random()->id,
            'date_emprunt' => $dateEmprunt,
            'date_retour_prevue' => $dateRetourPrevue,
            'date_retour_effectif' => $dateRetourEffectif,
        ]);
    }
}