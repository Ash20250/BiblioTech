<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmpruntTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usager_ne_peut_pas_emprunter_deux_livres_en_meme_temps(): void
    {
        $user = User::factory()->create();
        
        $ex1 = Exemplaire::factory()->create();
        $ex2 = Exemplaire::factory()->create();

        Emprunt::create([
            'usager_id' => $user->id,
            'exemplaire_id' => $ex1->id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->addDays(30),
            'date_retour_effectif' => null,
        ]);

$response = $this->actingAs($user)->post('/emprunts', [
    'usager_id' => $user->id,
    'exemplaire_id' => $ex2->id,
    'date_emprunt' => now()->format('Y-m-d'),        
    'date_retour_prevue' => now()->addDays(30)->format('Y-m-d'), 
]);

        $response->assertSessionHasErrors('usager_id');
        $this->assertEquals(1, Emprunt::where('usager_id', $user->id)->count());
    }
}