<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SalarieController, 
    EmpruntController, 
    ProfilController, 
    LivreController, 
    FavoriController, 
    RegistreController
};

Route::get('/', function () { return view('welcome'); });
Route::get('/catalogue', [LivreController::class, 'index'])->name('catalogue');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/mon-profil', [ProfilController::class, 'index'])->name('profile.index');
    
    Route::post('/favoris/{id}/toggle', [LivreController::class, 'toggleFavorite'])->name('livres.toggle-favorite');
    Route::delete('/favoris/{id}', [FavoriController::class, 'destroy'])->name('favoris.destroy');

    Route::delete('/reservations/{id}', [ProfilController::class, 'annulerReservation'])->name('reservations.destroy');
    Route::get('/registre', [RegistreController::class, 'index'])->name('registre.index');

    Route::prefix('livres')->name('livres.')->group(function () {
        Route::get('/nouveau', [LivreController::class, 'create'])->name('create');
        Route::post('/', [LivreController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [LivreController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch', 'post'], '/{id}', [LivreController::class, 'update'])->name('update');
        Route::delete('/{id}', [LivreController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('emprunts')->name('emprunts.')->group(function () {
        Route::get('/', [EmpruntController::class, 'index'])->name('index');
        Route::get('/nouveau', [EmpruntController::class, 'create'])->name('create');
        Route::post('/', [EmpruntController::class, 'store'])->name('store');
        
        // ROUTE CORRIGÉE : Pointe bien vers EmpruntController
        Route::patch('/{id}/retourner', [EmpruntController::class, 'retourner'])->name('retourner');
    });

    Route::post('/emprunter/{id}', [LivreController::class, 'emprunter'])->name('emprunter.livre');
    Route::post('/reserver/{id}', [LivreController::class, 'reserver'])->name('reserver.exemplaire');
    
    Route::get('/salaries', [SalarieController::class, 'index'])->name('salaries.index');
});

require __DIR__.'/auth.php';