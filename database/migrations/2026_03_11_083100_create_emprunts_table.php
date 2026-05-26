<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprunts', function (Blueprint $table) {
            $table->id();
            $table->date('date_emprunt');
            $table->date('date_retour_prevue'); 
            $table->date('date_retour_effectif')->nullable(); 
            
            // On garde usager_id comme tu l'as écrit
            $table->foreignId('usager_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('exemplaire_id')->constrained('exemplaires')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};