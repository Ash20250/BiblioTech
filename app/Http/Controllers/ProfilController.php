<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $emprunts = Emprunt::where('usager_id', $user->id)
            ->with(['exemplaire.livre.auteur'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        // CORRECTION : utilisation de date_retour_effectif
        $nbLivresEnCours = $emprunts->whereNull('date_retour_effectif')->count();

        $favoris = Favori::where('user_id', $user->id)
            ->with(['livre.auteur', 'livre.categorie'])
            ->get();

        $reservations = Exemplaire::where('reserved_by_user_id', $user->id)
            ->with(['livre.auteur'])
            ->get();

        return view('profil', compact('user', 'emprunts', 'nbLivresEnCours', 'favoris', 'reservations'));
    }

    public function annulerReservation($id)
    {
        $exemplaire = Exemplaire::where('id', $id)
            ->where('reserved_by_user_id', Auth::id())
            ->firstOrFail();

        $exemplaire->update(['reserved_by_user_id' => null]);

        return back()->with('success', 'Réservation annulée.');
    }

    public function retourner($id)
    {
        $emprunt = Emprunt::where('id', $id)
            ->where('usager_id', Auth::id())
            ->firstOrFail();

        // CORRECTION : mise à jour de la bonne colonne
        $emprunt->update(['date_retour_effectif' => now()]);

        // CORRECTION : remise à disposition de l'exemplaire pour le catalogue
        if ($emprunt->exemplaire) {
            $emprunt->exemplaire->update(['statut_id' => 1]);
        }

        return back()->with('success', 'Livre rendu avec succès.');
    }
}