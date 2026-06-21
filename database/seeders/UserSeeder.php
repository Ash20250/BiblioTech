<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Création de l'administrateur
        DB::table('users')->insert([
            'name' => 'Administrateur',
            'email' => 'admin@bibliotech.fr',
            'password' => Hash::make('password'),
            'is_admin' => 1,
        ]);

        // Création de l'adhérent
        DB::table('users')->insert([
            'name' => 'Adhérent Lambda',
            'email' => 'user@bibliotech.fr',
            'password' => Hash::make('password'),
            'is_admin' => 0,
        ]);
    }
}