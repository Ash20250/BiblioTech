<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * Ajoute ou retire un livre des favoris de l'utilisateur connecté.
     */
    public function toggle(Livre $livre)
    {
        // On récupère l'utilisateur actuellement connecté
        $user = Auth::user();

        // La méthode toggle() vérifie en base :
        // - Si l'ID du livre existe déjà pour cet utilisateur, il le supprime (Detach)
        // - S'il n'existe pas, il l'ajoute (Attach)
        $user->favoris()->toggle($livre->id);

        // On renvoie l'utilisateur sur la page précédente avec un message flash
        return back()->with('success', 'Vos favoris ont été mis à jour !');
    }
}