<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\User;
use App\Models\Exemplaire;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $query = Emprunt::with(['usager', 'exemplaire.livre']);
        
        if ($request->filled('statut')) {
            if ($request->statut === 'en_cours') {
                $query->whereNull('date_retour_effectif');
            } elseif ($request->statut === 'en_retard') {
                $query->whereNull('date_retour_effectif')
                      ->where('date_retour_prevue', '<', Carbon::now());
            }
        }

        $emprunts = $query->orderBy('date_emprunt', 'desc')->paginate(15);
        return view('emprunts.index', compact('emprunts'));
    }

    public function retourner($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        
        if ($emprunt->date_retour_effectif) {
            return redirect()->back()->with('error', 'Ce livre a déjà été retourné.');
        }

        $emprunt->update(['date_retour_effectif' => now()]);
        
        if ($emprunt->exemplaire) {
            $emprunt->exemplaire->update([
                'reserved_by_user_id' => null,
                'statut_id' => 1 
            ]);
        }

        return redirect()->back()->with('success', 'Le livre a été rendu avec succès.');
    }

    public function reserver(Exemplaire $exemplaire)
    {
        $exemplaire->update(['reserved_by_user_id' => Auth::id()]);
        return redirect()->back()->with('success', 'Réservation confirmée !');
    }

    public function annulerReservation(Exemplaire $exemplaire)
    {
        $exemplaire->update(['reserved_by_user_id' => null]);
        return redirect()->back()->with('success', 'La réservation a été annulée.');
    }
}