<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Nettoyage automatique des anciens profils de test (@biotech-test.fr) pour enlever les numéros
        $oldTestUsers = User::where('email', 'like', '%@biotech-test.fr')->get();
        foreach ($oldTestUsers as $oldUser) {
            Emprunt::where('usager_id', $oldUser->id)->delete();
            $oldUser->delete();
        }

        $exemplaires = Exemplaire::all();

        if ($exemplaires->isEmpty()) {
            $this->command->error("⚠️ Aucun exemplaire trouvé en base de données. Ajoute d'abord quelques livres !");
            return;
        }

        $prenoms = ['Lucas', 'Emma', 'Mathieu', 'Chloé', 'Hugo', 'Léa', 'Enzo', 'Manon', 'Arthur', 'Sarah', 'Nathan', 'Jade', 'Antoine', 'Camille', 'Louis', 'Zoé', 'Thomas', 'Inès', 'Maxime', 'Clara'];
        $noms = ['Martin', 'Bernard', 'Thomas', 'Petit', 'Robert', 'Richard', 'Durand', 'Dubois', 'Moreau', 'Laurent', 'Simon', 'Michel', 'Garcia', 'David', 'Bertrand', 'Roux', 'Vincent', 'Fournier', 'Morel', 'Girard'];

        $this->command->info("⏳ Suppression des anciens profils et génération de 50 usagers réalistes...");

        for ($i = 1; $i <= 50; $i++) {
            // ✅ Le nom reste parfaitement propre et réaliste désormais (ex: "Manon Dubois")
            $nomComplet = $prenoms[array_rand($prenoms)] . ' ' . $noms[array_rand($noms)];
            
            // ✅ On cache le numéro de boucle uniquement dans l'e-mail pour l'unicité SQL
            $email = Str::slug($nomComplet) . '-' . $i . '@biotech-test.fr';

            $user = User::create([
                'name' => $nomComplet,
                'email' => $email,
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]);

            // Attribution des emprunts
            $nombreEmprunts = rand(1, 2);
            for ($j = 0; $j < $nombreEmprunts; $j++) {
                $exemplaire = $exemplaires->random();
                $statutAleatoire = rand(1, 3);
                
                if ($statutAleatoire === 1) {
                    $dateEmprunt = Carbon::now()->subDays(rand(20, 40));
                    $datePrevue = (clone $dateEmprunt)->addDays(15);
                    $dateRetour = (clone $dateEmprunt)->addDays(rand(5, 14));
                } elseif ($statutAleatoire === 2) {
                    $dateEmprunt = Carbon::now()->subDays(rand(20, 30));
                    $datePrevue = (clone $dateEmprunt)->addDays(15);
                    $dateRetour = null;
                } else {
                    $dateEmprunt = Carbon::now()->subDays(rand(1, 10));
                    $datePrevue = (clone $dateEmprunt)->addDays(15);
                    $dateRetour = null;
                }

                Emprunt::create([
                    'usager_id'          => $user->id,
                    'exemplaire_id'      => $exemplaire->id,
                    'date_emprunt'       => $dateEmprunt,
                    'date_retour_prevue' => $datePrevue,
                    'date_retour'        => $dateRetour,
                ]);
            }
        }

        $this->command->info("✅ Remplacement terminé ! Les profils sont désormais impeccables.");
    }
}
