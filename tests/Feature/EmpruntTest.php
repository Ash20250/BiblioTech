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
        // 1. On crée un usager
        $user = User::factory()->create();
        
        // 2. On crée deux exemplaires complets grâce aux Factories
        // Les factories vont créer automatiquement l'auteur, la catégorie et le livre liés !
        $ex1 = Exemplaire::factory()->create();
        $ex2 = Exemplaire::factory()->create();

        // 3. On enregistre un premier emprunt en cours pour cet usager
        Emprunt::create([
            'usager_id' => $user->id,
            'exemplaire_id' => $ex1->id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->addDays(30),
            'date_retour_effectif' => null,
        ]);

        // 4. Action : On tente d'emprunter un DEUXIÈME exemplaire via la route
        $response = $this->actingAs($user)->post('/emprunts', [
            'usager_id' => $user->id,
            'exemplaire_id' => $ex2->id,
        ]);

        // 5. Assertions : On vérifie que le système bloque
        $response->assertSessionHasErrors('usager_id');
        $this->assertEquals(1, Emprunt::where('usager_id', $user->id)->count());
    }
}