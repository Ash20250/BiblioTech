<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Exemplaire; // INDISPENSABLE pour que la ligne 77 fonctionne
use Illuminate\Http\Request;

class LivreController extends Controller
{
    /**
     * Affiche le catalogue avec filtres de recherche
     */
    public function index(Request $request)
    {
        $query = Livre::with(['auteur', 'categorie', 'exemplaires.emprunts']);

        // Filtre par Titre ou Auteur
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

        // Filtre par Disponibilité
        if ($request->filled('disponible')) {
            $query->whereHas('exemplaires', function($q) {
                $q->whereDoesntHave('emprunts', function($e) {
                    $e->whereNull('date_retour_effectif');
                });
            });
        }

        $livres = $query->orderBy('titre')->paginate(10)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('catalogue', compact('livres', 'categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_create', compact('auteurs', 'categories'));
    }

    /**
     * Enregistre un nouveau livre + UN EXEMPLAIRE AUTOMATIQUE
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'auteur' => 'required|max:255',
            'theme' => 'nullable|max:255',
            'isbn' => 'nullable|max:20',
        ]);

        // 1. Gérer l'auteur et la catégorie
        $auteur = Auteur::firstOrCreate(['nom' => $request->auteur]);
        $nomCategorie = $request->theme ?? 'Général';
        $categorie = Categorie::firstOrCreate(['nom' => $nomCategorie]);

        // 2. Création du livre
        $livre = Livre::create([
            'titre' => $request->titre,
            'auteur_id' => $auteur->id,
            'categorie_id' => $categorie->id,
            'isbn' => $request->isbn,
        ]);

        // 3. CRÉATION DE L'EXEMPLAIRE (Pour éviter l'affichage "Épuisé")
        // Note : On utilise l'ID 1 pour le statut "Disponible" par défaut
        Exemplaire::create([
            'livre_id' => $livre->id,
            'statut_id' => 1, 
            'mise_en_service' => now(),
        ]);

        return redirect()->route('catalogue')->with('success', '✨ Nouveau livre ajouté et disponible en rayon !');
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit($id)
    {
        $livre = Livre::findOrFail($id);
        $auteurs = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        return view('livres_edit', compact('livre', 'auteurs', 'categories'));
    }

    /**
     * Met à jour un livre
     */
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

    /**
     * Supprime un livre
     */
    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);
        $livre->delete(); 
        return redirect()->back()->with('success', '🗑️ Le livre a été retiré du catalogue.');
    }
}