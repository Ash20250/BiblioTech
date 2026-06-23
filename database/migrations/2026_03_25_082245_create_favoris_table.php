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
        // On vérifie l'existence de la table pour éviter les conflits lors des tests
        if (!Schema::hasTable('favoris')) {
            Schema::create('favoris', function (Blueprint $table) {
                $table->id();
                // Assurez-vous que les tables 'users' et 'livres' existent bien avant
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};