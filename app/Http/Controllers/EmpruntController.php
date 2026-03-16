<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\User;
use App\Models\Exemplaire;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    /**
     * Affiche le registre complet (Rendus + En cours + Retards)
     */
    public function index()
    {
        // On récupère tout le monde avec pagination pour la performance (250 lignes)
        $emprunts = Emprunt::with(['usager', 'exemplaire.livre'])
            ->orderBy('date_emprunt', 'desc')
            ->paginate(15); 

        return view('emprunts', compact('emprunts'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $usagers = User::orderBy('name')->get();
        
        // On ne propose que les exemplaires réellement disponibles (sans emprunt en cours)
        $exemplaires = Exemplaire::whereDoesntHave('emprunts', function ($query) {
            $query->whereNull('date_retour_effectif');
        })->with('livre')->get(); 
        
        return view('emprunts_create', compact('usagers', 'exemplaires'));
    }

    /**
     * Enregistre l'emprunt (avec validation métier)
     */
    public function store(Request $request)
    {
        // 1. Validation de base
        $request->validate([
            'usager_id' => 'required|exists:users,id',
            'exemplaire_id' => 'required|exists:exemplaires,id',
        ]);

        // 2. Règle métier : Un usager ne peut avoir qu'un seul livre à la fois
        $dejaUnEmprunt = Emprunt::where('usager_id', $request->usager_id)
            ->whereNull('date_retour_effectif')
            ->first();

        if ($dejaUnEmprunt) {
            return redirect()->back()
                ->withErrors(['usager_id' => '❌ Cet usager doit d\'abord rendre son livre actuel.'])
                ->withInput();
        }

        // 3. Création de l'emprunt
        Emprunt::create([
            'usager_id' => $request->usager_id,
            'exemplaire_id' => $request->exemplaire_id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->addDays(30),
            'date_retour_effectif' => null,
        ]);

        return redirect()->route('emprunts.index')->with('success', '📜 Le prêt a été validé !');
    }

    /**
     * Marque un livre comme rendu
     */
    public function retourner($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $emprunt->update(['date_retour_effectif' => now()]);

        return redirect()->back()->with('success', '📖 Le livre est de retour en rayon.');
    }
}