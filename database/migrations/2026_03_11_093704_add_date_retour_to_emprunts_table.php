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
        Schema::table('emprunts', function (Blueprint $table) {
            // On a enlevé ->after('livre_id') pour éviter l'erreur de colonne inconnue.
            // La colonne se mettra simplement à la fin de la table.
            $table->timestamp('date_retour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            // En cas de "rollback", on supprime la colonne
            $table->dropColumn('date_retour');
        });
    }
};