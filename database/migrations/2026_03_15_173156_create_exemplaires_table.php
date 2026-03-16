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
    Schema::create('exemplaires', function (Blueprint $table) {
        $table->id();
        $table->date('mise_en_service');
        $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade');
        $table->foreignId('statut_id')->constrained('statuts')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exemplaires');
    }
};
