<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campus; 

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = ['Paris', 'Lyon', 'Marseille', 'Lille', 'Bordeaux', 'Nantes'];
        
        foreach ($campuses as $name) {
            Campus::create(['name' => $name]);
        }
    }
}