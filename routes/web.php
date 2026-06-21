<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\SalarieController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\ProfilController; 
use App\Http\Controllers\LivreController;
use App\Http\Controllers\FavoriController;

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
    
    // ✅ GESTION DES FAVORIS
    Route::post('/favoris/{livre}/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');

    // --- PROFIL ADHÉRENT ---
    Route::get('/mon-profil', [ProfilController::class, 'index'])->name('profile.index');

    // --- TABLEAU DE BORD ---
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- GESTION DES LIVRES (CRUD) ---
    Route::get('/livres/nouveau', [LivreController::class, 'create'])->name('livres.create');
    Route::post('/livres', [LivreController::class, 'store'])->name('livres.store');
    Route::get('/livres/{id}/modifier', [LivreController::class, 'edit'])->name('livres.edit');
    
    Route::match(['put', 'patch', 'post'], '/livres/{id}', [LivreController::class, 'update'])->name('livres.update');
    Route::delete('/livres/{id}', [LivreController::class, 'destroy'])->name('livres.destroy');

    // --- 📜 GESTION DES EMPRUNTS ---
    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/emprunts/nouveau', [EmpruntController::class, 'create'])->name('emprunts.create');
    Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
    Route::patch('/emprunts/{id}/retourner', [EmpruntController::class, 'retourner'])->name('emprunts.retourner');

    // --- EMPRUNT ET RÉSERVATION (Pointant vers LivreController selon notre logique) ---
    Route::post('/emprunter/{id}', [LivreController::class, 'emprunter'])->name('emprunter.livre');
    Route::post('/reserver/{id}', [LivreController::class, 'reserver'])->name('reserver.exemplaire');

    // Route pour annuler (laissée dans EmpruntController si tu préfères garder la logique là)
    Route::post('/reservation/annuler/{exemplaire}', [EmpruntController::class, 'annulerReservation'])->name('reservation.annuler');

    // --- GESTION DES SALARIÉS ---
    Route::get('/salaries', [SalarieController::class, 'index'])->name('salaries.index');
    Route::get('/salaries/nouveau', [SalarieController::class, 'create'])->name('salaries.create');
    Route::post('/salaries', [SalarieController::class, 'store'])->name('salaries.store');

});

// --- AUTRES ROUTES ---
Route::get('/campus', [CampusController::class, 'index']);
Route::get('/campus/{ville}', [CampusController::class, 'show']);

require __DIR__.'/auth.php';