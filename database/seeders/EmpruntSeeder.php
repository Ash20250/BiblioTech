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
        $autresUsagers = User::where('email', '!=', 'admin@test.com')->where('role', 'usager')->get();
        $exemplaires = Exemplaire::all();

        if ($exemplaires->isEmpty() || $autresUsagers->isEmpty()) {
            $this->command->error("Erreur : Assurez-vous d'avoir des livres et des usagers en base !");
            return;
        }

        // 1. Emprunts pour l'admin
        for ($i = 0; $i < 15; $i++) {
            $this->creerEmpruntAleatoire($admin, $exemplaires);
        }

        // 2. Emprunts pour les autres usagers
        for ($i = 0; $i < 235; $i++) {
            $this->creerEmpruntAleatoire($autresUsagers->random(), $exemplaires);
        }

        $this->command->info("250 emprunts générés avec succès !");
    }

    private function creerEmpruntAleatoire($user, $exemplaires)
    {
        $statutAleatoire = rand(1, 3);
        $dateEmprunt = Carbon::now();
        $dateRetourPrevue = null;
        $dateRetourEffectif = null;

        if ($statutAleatoire === 1) {
            // RENDU
            $dateEmprunt = Carbon::now()->subDays(rand(35, 100));
            $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
            $dateRetourEffectif = $dateEmprunt->copy()->addDays(rand(5, 25));
        } elseif ($statutAleatoire === 2) {
            // RETARD (Emprunté il y a plus de 30 jours, pas de retour)
            $dateEmprunt = Carbon::now()->subDays(rand(35, 60));
            $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
            $dateRetourEffectif = null;
        } else {
            // EN COURS
            $dateEmprunt = Carbon::now()->subDays(rand(1, 20));
            $dateRetourPrevue = $dateEmprunt->copy()->addDays(30);
            $dateRetourEffectif = null;
        }

        Emprunt::create([
            'usager_id'            => $user->id,
            'exemplaire_id'        => $exemplaires->random()->id,
            'date_emprunt'         => $dateEmprunt,
            'date_retour_prevue'   => $dateRetourPrevue,
            'date_retour_effectif' => $dateRetourEffectif,
        ]);
    }
}