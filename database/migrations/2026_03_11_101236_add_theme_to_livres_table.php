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
        Schema::table('livres', function (Blueprint $table) {
            // On ajoute la colonne 'theme'. 
            // nullable() évite les erreurs si tu as déjà des livres en base.
            // default('Général') donne une valeur par défaut automatique.
            $table->string('theme')->nullable()->after('titre')->default('Général');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            // Si on annule la migration, on supprime la colonne
            $table->dropColumn('theme');
        });
    }
};