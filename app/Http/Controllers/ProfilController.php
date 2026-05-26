<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        // 1. On récupère l'utilisateur connecté
        $user = Auth::user();

        // 2. Récupération des emprunts (Eager Loading pour la performance)
        $emprunts = Emprunt::where('usager_id', $user->id)
            ->with(['exemplaire.livre'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        // 3. RÉCUPÉRATION DES FAVORIS
        $favoris = $user->favoris()
            ->with(['auteur', 'categorie'])
            ->get();

        // 4. RÉCUPÉRATION DES RÉSERVATIONS
        // Basé uniquement sur la colonne reserved_by_user_id
        $reservations = Exemplaire::where('reserved_by_user_id', $user->id)
            ->with(['livre.auteur']) 
            ->get();

        // 5. Calcul du nombre d'emprunts actifs
        // ATTENTION : Vérifie si ta colonne s'appelle 'date_retour' ou 'date_retour_effectif'
        // Dans ton EmpruntController, tu utilises 'date_retour', donc je l'accorde ici :
        $nbLivresEnCours = $emprunts->where('date_retour', null)->count();

        // 6. On envoie tout à la vue profil.blade.php
        return view('profil', [
            'user' => $user,
            'emprunts' => $emprunts,
            'nbLivresEnCours' => $nbLivresEnCours,
            'favoris' => $favoris,
            'reservations' => $reservations
        ]);
    }
}