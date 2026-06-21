<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@bibliotech.fr',
            'password' => Hash::make('password123'), // Remplace par un vrai mot de passe
            'is_admin' => true, // Assure-toi que ton modèle User a bien ce champ
        ]);
    }
}
