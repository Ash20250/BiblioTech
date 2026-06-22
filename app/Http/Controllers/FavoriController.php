<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Favori; // Assurez-vous d'importer le modèle si vous l'utilisez
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * Ajoute ou retire un livre des favoris (via Toggle)
     */
    public function toggle(Livre $livre)
    {
        $user = Auth::user();
        $user->favoris()->toggle($livre->id);

        return back()->with('success', 'Favoris mis à jour !');
    }

    /**
     * Supprime un favori spécifiquement depuis l'ID du favori (utilisé dans le profil)
     */
    public function destroy($id)
    {
        // On cherche le favori appartenant à l'utilisateur connecté pour éviter les injections
        $favori = Favori::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $favori->delete();

        return back()->with('success', 'Le livre a été retiré de vos favoris.');
    }
}