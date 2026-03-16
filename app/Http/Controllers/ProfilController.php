<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprunt;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    /**
     * Affiche le profil de l'usager connecté avec son historique
     * Répond au point 2.a du Cahier des Charges
     */
    public function index()
    {
        // 1. On récupère l'utilisateur connecté
        $user = Auth::user();

        // 2. On récupère ses emprunts (Eager Loading de exemplaire.livre pour éviter les requêtes N+1)
        $emprunts = Emprunt::where('usager_id', $user->id)
            ->with(['exemplaire.livre'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        // 3. Calcul du nombre d'emprunts actifs (pour l'affichage des alertes)
        $nbLivresEnCours = $emprunts->where('date_retour_effectif', null)->count();

        // 4. On envoie tout à la vue resources/views/profil.blade.php
        return view('profil', compact('user', 'emprunts', 'nbLivresEnCours'));
    }
}