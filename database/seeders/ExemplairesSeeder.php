<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livre;
use App\Models\Exemplaire;
use Illuminate\Support\Facades\DB;

class ExemplairesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On vide la table pour repartir à zéro et éviter les cumuls
        DB::table('exemplaires')->truncate();

        $livres = Livre::all();

        foreach ($livres as $livre) {
            // 2. On génère un nombre aléatoire entre 0 et 3
            // 0 = Aucun exemplaire (Indisponible)
            // 1 à 3 = Nombre d'exemplaires disponibles
            $nbCopies = rand(0, 3);

            for ($i = 0; $i < $nbCopies; $i++) {
                Exemplaire::create([
                    'livre_id' => $livre->id,
                    'code_barre' => 'GEN-' . strtoupper(uniqid()),
                    'mise_en_service' => now(),
                    'statut_id' => 1, // ID du statut 'Disponible'
                ]);
            }
        }
    }
}
