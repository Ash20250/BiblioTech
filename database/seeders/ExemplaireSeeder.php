<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use Carbon\Carbon;

class ExemplaireSeeder extends Seeder
{
    public function run(): void
    {
        $livres = Livre::all();
        $statuts = Statut::all();

        if ($livres->isEmpty()) {
            throw new \Exception("Aucun livre trouvé en base.");
        }

        if ($statuts->isEmpty()) {
            throw new \Exception("Aucun statut trouvé en base.");
        }

        for ($i = 0; $i < 100; $i++) {
            $livre = $livres->random();
            $statut = $statuts->random();

            Exemplaire::create([
                'livre_id' => $livre->id,
                'statut_id' => $statut->id,
                'mise_en_service' => Carbon::now()->subDays(rand(1, 1000)),
            ]);
        }
    }
}
