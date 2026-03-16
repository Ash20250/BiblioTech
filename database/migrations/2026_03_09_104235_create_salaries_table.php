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
    Schema::create('salaries', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('sexe');  // <--- IL MANQUE SÛREMENT ÇA
        $table->integer('age');  // <--- ET ÇA
        $table->string('email');
        $table->string('poste');
        $table->string('ville');
        $table->string('remote');
        $table->foreignId('campus_id')->constrained(); // Pour ta Factory
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
