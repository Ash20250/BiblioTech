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
    /**
     * Affiche le registre avec filtres
     */
    public function index(Request $request)
    {
        // Chargement eager (with) indispensable pour éviter "Usager inconnu"
        $query = Emprunt::with(['usager', 'exemplaire.livre']);

        // Application des filtres de statut
        if ($request->filled('statut')) {
            if ($request->statut === 'en_cours') {
                $query->whereNull('date_retour_effectif');
            } elseif ($request->statut === 'en_retard') {
                $query->whereNull('date_retour_effectif')
                      ->where('date_retour_prevue', '<', Carbon::now());
            }
        }

        // Filtre par date prévue
        if ($request->filled('date_prevue')) {
            $query->whereDate('date_retour_prevue', $request->date_prevue);
        }

        $emprunts = $query->orderBy('date_emprunt', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        return view('emprunts.index', compact('emprunts'));
    }

    public function create()
    {
        $users = User::where('role', 'usager')->orderBy('name')->get();
        
        $exemplaires = Exemplaire::with('livre')
            ->whereDoesntHave('emprunts', function ($query) {
                $query->whereNull('date_retour_effectif');
            })->get();

        return view('emprunts.create', compact('users', 'exemplaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usager_id'          => 'required|exists:users,id',
            'exemplaire_id'      => 'required|exists:exemplaires,id',
            'date_emprunt'       => 'required|date',
            'date_retour_prevue' => 'required|date|after_or_equal:date_emprunt',
        ]);

        $exemplaire = Exemplaire::findOrFail($validated['exemplaire_id']);

        if ($exemplaire->reserved_by_user_id && $exemplaire->reserved_by_user_id != $validated['usager_id']) {
            return redirect()->back()->withErrors(['exemplaire_id' => '❌ Exemplaire réservé par un autre usager.'])->withInput();
        }

        if (Emprunt::where('usager_id', $validated['usager_id'])->whereNull('date_retour_effectif')->count() >= 5) {
            return redirect()->back()->withErrors(['usager_id' => '❌ Quota de 5 livres atteint.'])->withInput();
        }

        Emprunt::create($validated + ['date_retour_effectif' => null]);

        $exemplaire->update(['reserved_by_user_id' => null]);

        return redirect()->route('emprunts.index')->with('success', '📜 Prêt validé avec succès !');
    }

    public function emprunterParUsager(Livre $livre)
    {
        $user = Auth::user();
        if (Emprunt::where('usager_id', $user->id)->whereNull('date_retour_effectif')->count() >= 5) {
            return redirect()->back()->with('error', '⚠️ Quota de 5 livres atteint !');
        }

        $exemplaire = $livre->exemplaires()
            ->where(function($query) use ($user) {
                $query->whereNull('reserved_by_user_id')->orWhere('reserved_by_user_id', $user->id);
            })
            ->whereDoesntHave('emprunts', function($q) {
                $q->whereNull('date_retour_effectif');
            })->first();

        if (!$exemplaire) {
            return redirect()->back()->with('error', 'Désolé, ce livre n\'est plus disponible.');
        }

        Emprunt::create([
            'usager_id'            => $user->id,
            'exemplaire_id'        => $exemplaire->id,
            'date_emprunt'         => now(),
            'date_retour_prevue'   => now()->addDays(30),
            'date_retour_effectif' => null,
        ]);

        $exemplaire->update(['reserved_by_user_id' => null]);

        return redirect()->back()->with('success', '📖 Livre emprunté avec succès !');
    }

    public function reserver(Exemplaire $exemplaire)
    {
        if (!is_null($exemplaire->reserved_by_user_id)) {
            return redirect()->back()->with('error', '⚠️ Déjà réservé.');
        }
        $exemplaire->update(['reserved_by_user_id' => Auth::id()]);
        return redirect()->back()->with('success', '✅ Réservation confirmée !');
    }

    public function annulerReservation(Exemplaire $exemplaire)
    {
        if ($exemplaire->reserved_by_user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }
        $exemplaire->update(['reserved_by_user_id' => null]);
        return redirect()->back()->with('success', 'La réservation a été annulée.');
    }

    public function retourner($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        
        // Sécurité : Vérifier s'il n'est pas déjà retourné
        if ($emprunt->date_retour_effectif) {
            return redirect()->back()->with('error', '⚠️ Déjà retourné.');
        }

        $emprunt->update(['date_retour_effectif' => now()]);
        
        if ($emprunt->exemplaire) {
            $emprunt->exemplaire->update(['reserved_by_user_id' => null]);
        }

        return redirect()->back()->with('success', '📖 Le livre est de retour.');
    }
}