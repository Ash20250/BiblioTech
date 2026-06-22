<?php

namespace App\Http\Controllers;

use App\Models\Emprunt; // Assurez-vous d'importer votre modèle
use Illuminate\Http\Request;

class RegistreController extends Controller
{
    public function index(Request $request)
    {
        // On récupère tous les emprunts
        $query = Emprunt::query();

        // Si un filtre est appliqué (ex: en cours, en retard)
        if ($request->has('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }

        // On récupère les résultats
        $emprunts = $query->get();

        // On envoie les données à la vue
        return view('registre.index', compact('emprunts'));
    }
}