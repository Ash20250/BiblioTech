<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statut;

class StatutSeeder extends Seeder
{
    public function run(): void
    {
        Statut::create(['statut' => 'Disponible']);
        Statut::create(['statut' => 'Emprunté']);
        Statut::create(['statut' => 'Réservé']);
        Statut::create(['statut' => 'Perdu']);
    }
}
