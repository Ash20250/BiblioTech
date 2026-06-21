<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salarie;

class SalarieController extends Controller
{
    /**
     * 1. AFFICHER & RECHERCHER (Read)
     */
    public function index(Request $request) 
    {
        $recherche = $request->input('search');

        if ($recherche) {
            $salaries = Salarie::where('nom', 'LIKE', "%{$recherche}%")
                                 ->orWhere('poste', 'LIKE', "%{$recherche}%")
                                 ->get();
        } else {
            $salaries = Salarie::all();
        }

        return view('catalogue', ['salaries' => $salaries]);
    }

    /**
     * 2. RECHERCHE AJAX (Pour ton script Fetch)
     */
    public function search(Request $request)
    {
        $recherche = $request->input('search');
        
        $salaries = Salarie::where('nom', 'LIKE', "%{$recherche}%")
                            ->orWhere('poste', 'LIKE', "%{$recherche}%")
                            ->get();

        return response()->json($salaries);
    }

    /**
     * 3. FORMULAIRE DE CRÉATION (Create)
     * Correction de l'erreur "Call to undefined method create()"
     */
    public function create()
    {
        return view('salaries_create');
    }

    /**
     * 4. ENREGISTRER UN NOUVEAU SALARIÉ (Store)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'poste' => 'required|string|max:255',
            'email' => 'required|email|unique:salaries,email',
            'ville' => 'required|string|max:100',
            'remote' => 'nullable|boolean'
        ]);

        // Gestion du booléen pour le télétravail
        $validated['remote'] = $request->has('remote');

        Salarie::create($validated);

        return redirect()->route('catalogue')->with('success', 'Nouveau salarié inscrit avec succès.');
    }

    /**
     * 5. FORMULAIRE DE MODIFICATION (Edit)
     */
    public function edit($id)
    {
        $salarie = Salarie::findOrFail($id);
        return view('salaries_edit', compact('salarie'));
    }

    /**
     * 6. ENREGISTRER LA MODIFICATION (Update)
     */
    public function update(Request $request, $id)
    {
        $salarie = Salarie::findOrFail($id);

        $request->validate([
            'nom' => 'required|max:255',
            'poste' => 'required|max:255',
            'email' => 'required|email|unique:salaries,email,' . $id,
            'ville' => 'required|string|max:100',
            'remote' => 'nullable'
        ]);

        $data = $request->all();
        $data['remote'] = $request->has('remote');

        $salarie->update($data);

        return redirect()->route('catalogue')->with('success', 'La fiche de ' . $salarie->nom . ' a été mise à jour.');
    }

    /**
     * 7. SUPPRIMER UN SALARIÉ (Delete)
     */
    public function destroy($id)
    {
        $salarie = Salarie::findOrFail($id);
        $salarie->delete();

        return redirect()->route('catalogue')->with('success', 'Le salarié a été retiré du registre.');
    }
}