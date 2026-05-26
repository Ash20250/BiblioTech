<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\User;
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
                $query->whereNull('date_retour');
            } 
            elseif ($request->statut === 'en_retard') {
                $query->whereNull('date_retour')
                      ->where('date_retour_prevue', '<', now());
            }
        }

        if ($request->filled('date_prevue')) {
            $query->whereDate('date_retour_prevue', $request->date_prevue);
        }

        $emprunts = $query->orderBy('date_emprunt', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        return view('emprunts', compact('emprunts'));
    }

    public function create()
    {
        $users = User::where('role', 'CLIENT')->get();

        // ✅ APPEL SÉCURISÉ
        $exemplaires = \App\Models\Exemplaire::with('livre')
            ->whereDoesntHave('emprunts', function ($query) {
                $query->whereNull('date_retour');
            })->get();

        return view('emprunts.create', compact('users', 'exemplaires'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usager_id'          => 'required|exists:users,id',
            'exemplaire_id'      => 'required|exists:exemplaires,id',
            'date_emprunt'       => 'required|date',
            'date_retour_prevue' => 'required|date|after_or_equal:date_emprunt',
        ]);

        $exemplaire = \App\Models\Exemplaire::findOrFail($request->exemplaire_id);

        if ($exemplaire->reserved_by_user_id && $exemplaire->reserved_by_user_id != $request->usager_id) {
            return redirect()->back()
                ->withErrors(['exemplaire_id' => '❌ Cet exemplaire est réservé par un autre usager.'])
                ->withInput();
        }

        $nbEmpruntsActuels = Emprunt::where('usager_id', $request->usager_id)
            ->whereNull('date_retour')
            ->count();

        if ($nbEmpruntsActuels >= 5) {
            return redirect()->back()
                ->withErrors(['usager_id' => '❌ Cet usager a déjà atteint la limite de 5 livres.'])
                ->withInput();
        }

        Emprunt::create([
            'usager_id'          => $request->usager_id,
            'exemplaire_id'      => $request->exemplaire_id,
            'date_emprunt'       => $request->date_emprunt,
            'date_retour_prevue' => $request->date_retour_prevue,
            'date_retour'        => null, 
        ]);

        $exemplaire->update(['reserved_by_user_id' => null]);

        return redirect()->route('emprunts.index')->with('success', '📜 Le prêt a été validé !');
    }

    public function emprunterParUsager(Livre $livre)
    {
        $user = auth()->user();

        $nbEmpruntsActuels = Emprunt::where('usager_id', $user->id)
            ->whereNull('date_retour')
            ->count();

        if ($nbEmpruntsActuels >= 5) {
            return redirect()->back()->with('error', '⚠️ Quota de 5 livres atteint !');
        }

        $exemplaire = $livre->exemplaires()
            ->where(function($query) use ($user) {
                $query->whereNull('reserved_by_user_id')
                      ->orWhere('reserved_by_user_id', $user->id);
            })
            ->whereDoesntHave('emprunts', function($q) {
                $q->whereNull('date_retour');
            })->first();

        if (!$exemplaire) {
            return redirect()->back()->with('error', 'Désolé, ce livre n\'est plus disponible.');
        }

        Emprunt::create([
            'usager_id'          => $user->id,
            'exemplaire_id'      => $exemplaire->id,
            'date_emprunt'       => now(),
            'date_retour_prevue' => now()->addDays(30),
            'date_retour'        => null,
        ]);

        $exemplaire->update(['reserved_by_user_id' => null]);

        return redirect()->back()->with('success', '📖 Livre emprunté avec succès !');
    }

    public function reserver(\App\Models\Exemplaire $exemplaire)
    {
        if (!is_null($exemplaire->reserved_by_user_id)) {
            return redirect()->back()->with('error', '⚠️ Déjà réservé.');
        }

        $exemplaire->update([
            'reserved_by_user_id' => Auth::id() 
        ]);

        return redirect()->back()->with('success', '✅ Réservation confirmée !');
    }

    public function annulerReservation(\App\Models\Exemplaire $exemplaire)
    {
        if ($exemplaire->reserved_by_user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $exemplaire->update([
            'reserved_by_user_id' => null
        ]);

        return redirect()->back()->with('success', 'La réservation a été annulée.');
    }

    public function retourner($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        
        $emprunt->update([
            'date_retour' => now()
        ]);

        $emprunt->exemplaire->update([
            'reserved_by_user_id' => null 
        ]);

        return redirect()->back()->with('success', '📖 Le livre est de retour.');
    }
}