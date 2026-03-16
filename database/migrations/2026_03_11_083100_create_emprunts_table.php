<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('emprunts', function (Blueprint $table) {
        $table->id();
        $table->date('date_emprunt');
        $table->date('date_retour_prevue'); // On calculera +30 jours dans le code
        $table->date('date_retour_effectif')->nullable(); // Pour savoir quand il a été rendu
        
        // Liens selon l'UML
        $table->foreignId('usager_id')->constrained('users')->onDelete('cascade'); 
        $table->foreignId('exemplaire_id')->constrained('exemplaires')->onDelete('cascade');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};
