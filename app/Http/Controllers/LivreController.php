<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Exemplaire;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivreController extends Controller
{
    /**
     * Affiche le catalogue avec filtres
     * Accessible par : Visiteur (non-connecté), Usager, Admin
     */
    public function index(Request $request)
    {
        // On charge les relations pour éviter les erreurs "null" sur l'auteur ou catégorie
        $query = Livre::with(['auteur', 'categorie', 'exemplaires.emprunts']);

        // Filtre Recherche : Titre ou Nom d'Auteur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('auteur', function($a) use ($search) {
                      $a->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par Catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre Disponibilité : Uniquement les livres ayant au moins un exemplaire libre
        if ($request->filled('disponible')) {
            $query->whereHas('exemplaires', function($q) {
                $q->whereDoesntHave('emprunts', function($e) {
                    $e->whereNull('date_retour'); 
                });
            });
        }

        $livres = $query->orderBy('titre')->paginate(10)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('catalogue', compact('livres', 'categories'));
    }

    /**
     * Formulaire d'ajout (Admin uniquement via Middleware dans les routes)
     */
    public function create()
    {
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_create', compact('auteurs', 'categories'));
    }

    /**
     * Enregistre un livre et crée automatiquement un exemplaire physique
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255|unique:livres,titre',
            'auteur' => 'required|max:255', // Nom de l'auteur tapé à la main
            'theme' => 'nullable|max:255',
            'isbn' => 'nullable|max:20',
        ], [
            'titre.unique' => '📚 Ce titre existe déjà dans la bibliothèque.'
        ]);

        // Évite de créer des doublons d'auteurs ou de catégories
        $auteur = Auteur::firstOrCreate(['nom' => $request->auteur]);
        $nomCategorie = $request->theme ?? 'Général';
        $categorie = Categorie::firstOrCreate(['nom' => $nomCategorie]);

        $livre = Livre::create([
            'titre' => $request->titre,
            'auteur_id' => $auteur->id,
            'categorie_id' => $categorie->id,
            'isbn' => $request->isbn,
        ]);

        // Création immédiate d'un exemplaire pour qu'il soit "En rayon"
        Exemplaire::create([
            'livre_id' => $livre->id,
            'statut_id' => 1, // ID correspondant au statut 'Disponible'
            'mise_en_service' => now(),
        ]);

        return redirect()->route('catalogue')->with('success', '✨ Nouveau livre ajouté et disponible en rayon !');
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $livre = Livre::findOrFail($id);
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_edit', compact('livre', 'auteurs', 'categories'));
    }

    /**
     * Mise à jour des infos du livre
     */
    public function update(Request $request, $id)
    {
        $livre = Livre::findOrFail($id);
        
        $request->validate([
            'titre' => ['required', 'max:255', Rule::unique('livres')->ignore($livre->id)],
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
        ], [
            'titre.unique' => '❌ Ce titre est déjà utilisé par un autre ouvrage.'
        ]);

        $livre->update($request->all()); 
        
        return redirect()->route('catalogue')->with('success', '💾 Le livre a été modifié avec succès.');
    }

    /**
     * Suppression (Retire le livre et ses exemplaires)
     */
    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);
        $livre->delete(); 
        return redirect()->back()->with('success', '🗑️ Le livre a été retiré du catalogue.');
    }
}