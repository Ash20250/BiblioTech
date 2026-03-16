<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    /**
     * Affiche le catalogue avec filtres de recherche (Titre, Auteur, Catégorie, Dispo)
     */
    public function index(Request $request)
    {
        // 1. On prépare la requête avec les relations pour éviter le problème N+1
        $query = Livre::with(['auteur', 'categorie', 'exemplaires.emprunts']);

        // 2. Filtre par Titre ou Auteur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhereHas('auteur', function($a) use ($search) {
                      $a->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Filtre par Catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // 4. Filtre par Disponibilité (Uniquement ceux qui ont au moins un exemplaire libre)
        if ($request->filled('disponible')) {
            $query->whereHas('exemplaires', function($q) {
                $q->whereDoesntHave('emprunts', function($e) {
                    $e->whereNull('date_retour_effectif');
                });
            });
        }

        // 5. Récupération avec pagination (indispensable pour 400 livres)
        $livres = $query->orderBy('titre')->paginate(10)->withQueryString();
        
        // On récupère aussi les catégories pour le menu déroulant de recherche
        $categories = Categorie::orderBy('nom')->get();

        return view('catalogue', compact('livres', 'categories'));
    }

    /**
     * Enregistre un nouveau livre (CRUD)
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        Livre::create($request->all()); 

        return redirect()->route('catalogue')->with('success', '✨ Nouveau livre ajouté au catalogue !');
    }

    // ... Garde tes méthodes create, edit, update et destroy telles quelles ...
    
    public function create()
    {
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_create', compact('auteurs', 'categories'));
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
            'titre' => 'required|max:255',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        $livre->update($request->all()); 
        return redirect()->route('catalogue')->with('success', '💾 Le livre a été modifié avec succès.');
    }

    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);
        $livre->delete(); 
        return redirect()->back()->with('success', '🗑️ Le livre a été retiré du catalogue.');
    }
}