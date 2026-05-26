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
    Schema::table('exemplaires', function (Blueprint $table) {
        // On vérifie si la colonne n'existe pas déjà avant de l'ajouter
        if (!Schema::hasColumn('exemplaires', 'is_reserved')) {
            $table->boolean('is_reserved')->default(false);
        }

        // C'est celle-ci qui nous manque pour le profil !
        if (!Schema::hasColumn('exemplaires', 'reserved_by_user_id')) {
            $table->unsignedBigInteger('reserved_by_user_id')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemplaires', function (Blueprint $table) {
            // On supprime les colonnes si on annule la migration
            $table->dropColumn(['is_reserved', 'reserved_by_user_id']);
        });
    }
};