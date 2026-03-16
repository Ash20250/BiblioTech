<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\SalarieController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\ProfilController; 
use App\Http\Controllers\LivreController;

/*
|--------------------------------------------------------------------------
| Web Routes - BiblioTech
|--------------------------------------------------------------------------
*/

// --- PAGES PUBLIQUES ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogue', [LivreController::class, 'index'])->name('catalogue');
Route::redirect('/emprunt', '/emprunts');

// --- ZONE SÉCURISÉE (Connexion obligatoire) ---
Route::middleware('auth')->group(function () {
    
    // --- PROFIL ADHÉRENT ---
    Route::get('/mon-profil', [ProfilController::class, 'index'])->name('profil.usager');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- GESTION DES LIVRES (CRUD) ---
    Route::get('/livres/nouveau', [LivreController::class, 'create'])->name('livres.create');
    Route::post('/livres', [LivreController::class, 'store'])->name('livres.store');
    Route::get('/livres/{id}/modifier', [LivreController::class, 'edit'])->name('livres.edit');
    
    /** * FIX FINAL ANTI-ERREUR 405 
     * On accepte PUT, PATCH et POST pour parer à tout conflit de verbe HTTP
     */
    Route::match(['put', 'patch', 'post'], '/livres/{id}', [LivreController::class, 'update'])->name('livres.update');
    
    Route::delete('/livres/{id}', [LivreController::class, 'destroy'])->name('livres.destroy');

    // --- GESTION DES EMPRUNTS ---
    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/emprunts/nouveau', [EmpruntController::class, 'create'])->name('emprunt.create');
    Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunt.store'); 
    Route::patch('/emprunts/{id}/retourner', [EmpruntController::class, 'retourner'])->name('emprunts.retourner');

    // --- GESTION DES SALARIÉS ---
    Route::get('/salaries', [SalarieController::class, 'index'])->name('salaries.index');
    Route::get('/salaries/nouveau', [SalarieController::class, 'create'])->name('salaries.create');
    Route::post('/salaries', [SalarieController::class, 'store'])->name('salaries.store');

});

// --- AUTRES ROUTES ---
Route::get('/campus', [CampusController::class, 'index']);
Route::get('/campus/{ville}', [CampusController::class, 'show']);

require __DIR__.'/auth.php';