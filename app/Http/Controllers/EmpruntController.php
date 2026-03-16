<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\User;
use App\Models\Exemplaire;
use App\Models\Livre; // Ajouté pour le bouton emprunter
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    /**
     * Affiche le registre complet (Rendus + En cours + Retards)
     * Accessible au Bibliothécaire
     */
    public function index()
    {
        // On récupère tout le monde avec pagination pour la performance
        $emprunts = Emprunt::with(['usager', 'exemplaire.livre'])
            ->orderBy('date_emprunt', 'desc')
            ->paginate(15); 

        return view('emprunts', compact('emprunts'));
    }

    /**
     * Affiche le formulaire de création (Back-office)
     */
    public function create()
    {
        $usagers = User::orderBy('name')->get();
        
        // On ne propose que les exemplaires réellement disponibles
        $exemplaires = Exemplaire::whereDoesntHave('emprunts', function ($query) {
            $query->whereNull('date_retour_effectif');
        })->with('livre')->get(); 
        
        return view('emprunts_create', compact('usagers', 'exemplaires'));
    }

    /**
     * Enregistre l'emprunt via le bibliothécaire
     */
    public function store(Request $request)
    {
        $request->validate([
            'usager_id' => 'required|exists:users,id',
            'exemplaire_id' => 'required|exists:exemplaires,id',
        ]);

        // Règle métier : Un usager ne peut avoir qu'un seul livre à la fois
        $dejaUnEmprunt = Emprunt::where('usager_id', $request->usager_id)
            ->whereNull('date_retour_effectif')
            ->first();

        if ($dejaUnEmprunt) {
            return redirect()->back()
                ->withErrors(['usager_id' => '❌ Cet usager doit d\'abord rendre son livre actuel.'])
                ->withInput();
        }

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
     * ✅ MÉTHODE POUR L'USAGER (Bouton Catalogue)
     * Répond au cahier des charges : "Un usager peut emprunter"
     */
    public function emprunterParUsager(Livre $livre)
    {
        $user = auth()->user();

        // 1. Vérification : Un seul livre à la fois
        $dejaUnEmprunt = Emprunt::where('usager_id', $user->id)
            ->whereNull('date_retour_effectif')
            ->exists();

        if ($dejaUnEmprunt) {
            return redirect()->back()->with('error', '⚠️ Vous avez déjà un emprunt en cours. Rendez-le pour en prendre un nouveau !');
        }

        // 2. Trouver un exemplaire disponible pour ce livre
        $exemplaire = $livre->exemplaires()->whereDoesntHave('emprunts', function($q) {
            $q->whereNull('date_retour_effectif');
        })->first();

        if (!$exemplaire) {
            return redirect()->back()->with('error', 'Désolé, ce livre n\'est plus disponible en rayon.');
        }

        // 3. Création de l'emprunt (30 jours)
        Emprunt::create([
            'usager_id' => $user->id,
            'exemplaire_id' => $exemplaire->id,
            'date_emprunt' => now(),
            'date_retour_prevue' => now()->addDays(30),
        ]);

        return redirect()->route('catalogue')->with('success', '📖 Livre emprunté avec succès ! Il apparaît maintenant dans votre profil.');
    }

    /**
     * Marque un livre comme rendu (Action du Bibliothécaire)
     */
    public function retourner($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $emprunt->update(['date_retour_effectif' => now()]);

        return redirect()->back()->with('success', '📖 Le livre est de retour en rayon.');
    }
}