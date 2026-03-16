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
            // On ajoute la colonne. nullable() est crucial car 
            // au moment de l'emprunt, la date de retour est inconnue.
            $table->timestamp('date_retour')->nullable()->after('livre_id');
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