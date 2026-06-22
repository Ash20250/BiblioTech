<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivreController extends Controller
{
    /**
     * Affiche le catalogue avec filtres et relations nécessaires
     */
    public function index(Request $request)
    {
        $query = Livre::with(['auteur', 'categorie', 'exemplaires.emprunts', 'favoris']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('auteur', function($a) use ($search) {
                      $a->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $livres = $query->orderBy('titre')->paginate(10)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('catalogue', compact('livres', 'categories'));
    }

    /**
     * Bascule le statut favori du livre pour l'utilisateur connecté
     */
    public function toggleFavorite($id)
    {
        $livre = Livre::findOrFail($id);
        $user = Auth::user();

        $favori = Favori::where('user_id', $user->id)
                        ->where('livre_id', $livre->id)
                        ->first();

        if ($favori) {
            $favori->delete();
            $message = 'Retiré de vos coups de cœur.';
        } else {
            Favori::create([
                'user_id' => $user->id,
                'livre_id' => $livre->id,
            ]);
            $message = 'Ajouté à vos coups de cœur !';
        }

        return back()->with('success', $message);
    }

    public function create()
    {
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255|unique:livres,titre',
            'auteur' => 'required|max:255',
            'theme' => 'nullable|max:255',
            'isbn' => 'nullable|max:20',
        ]);

        $auteur = Auteur::firstOrCreate(['nom' => $request->auteur]);
        $nomCategorie = $request->theme ?? 'Général';
        $categorie = Categorie::firstOrCreate(['nom' => $nomCategorie]);

        $livre = Livre::create([
            'titre' => $request->titre,
            'auteur_id' => $auteur->id,
            'categorie_id' => $categorie->id,
            'isbn' => $request->isbn,
        ]);

        Exemplaire::create([
            'livre_id' => $livre->id,
            'statut_id' => 1,
            'mise_en_service' => now(),
        ]);

        return redirect()->route('catalogue')->with('success', '✨ Nouveau livre ajouté !');
    }

    public function edit($id)
    {
        $livre = Livre::findOrFail($id);
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_edit', compact('livre', 'auteurs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $livre = Livre::findOrFail($id);
        
        $request->validate([
            'titre' => ['required', 'max:255', Rule::unique('livres')->ignore($livre->id)],
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $livre->update($request->all()); 
        return redirect()->route('catalogue')->with('success', '💾 Le livre a été modifié.');
    }

    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);
        $livre->delete(); 
        return redirect()->back()->with('success', '🗑️ Le livre a été retiré.');
    }

    public function emprunter($id)
    {
        $livre = Livre::findOrFail($id);
        
        $exemplaire = $livre->exemplaires()
            ->whereNull('reserved_by_user_id')
            ->whereDoesntHave('emprunts', function($query) {
                $query->whereNull('date_retour');
            })
            ->first();

        if (!$exemplaire) {
            return back()->with('error', 'Désolé, aucun exemplaire n\'est disponible en rayon.');
        }

        Emprunt::create([
            'usager_id' => Auth::id(), 
            'exemplaire_id' => $exemplaire->id,
            'date_emprunt' => Carbon::now(),
            'date_retour_prevue' => Carbon::now()->addDays(30), 
        ]);

        $exemplaire->update(['statut_id' => 2]); 

        return back()->with('success', 'Le livre "' . $livre->titre . '" a été emprunté.');
    }

    public function rendre($emprunt_id)
    {
        $emprunt = Emprunt::findOrFail($emprunt_id);
        $emprunt->update(['date_retour' => Carbon::now()]);

        if ($emprunt->exemplaire) {
            $emprunt->exemplaire->update(['statut_id' => 1]);
        }

        return back()->with('success', 'Livre retourné !');
    }

    public function reserver($exemplaire_id)
    {
        $exemplaire = Exemplaire::findOrFail($exemplaire_id);

        if ($exemplaire->reserved_by_user_id !== null) {
            return back()->with('error', 'Cet exemplaire est déjà réservé.');
        }

        $exemplaire->update([
            'reserved_by_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Réservation effectuée avec succès !');
    }
}