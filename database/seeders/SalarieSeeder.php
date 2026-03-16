<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salarie;

class SalarieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salarie::factory()->count(60)->create();
    }
}