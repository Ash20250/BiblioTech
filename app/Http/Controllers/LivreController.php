<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Exemplaire;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivreController extends Controller
{
    /**
     * Affiche le catalogue avec filtres
     */
    public function index(Request $request)
    {
        $query = Livre::with(['auteur', 'categorie', 'exemplaires.emprunts']);

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

    /**
     * Action pour Emprunter un livre
     */
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
            'user_id' => Auth::id(),
            'exemplaire_id' => $exemplaire->id,
            'date_emprunt' => Carbon::now(),
        ]);

        $exemplaire->update(['statut_id' => 2]); 

        return back()->with('success', 'Le livre "' . $livre->titre . '" a été emprunté.');
    } // <-- Accolade de fermeture de la méthode emprunter

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
    } // <-- Accolade de fermeture de la méthode reserver

} // <-- CETTE ACCOLADE EST OBLIGATOIRE : elle ferme la classe LivreController