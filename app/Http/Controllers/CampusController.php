<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = ['Aix', 'Nantes', 'Rennes', 'Toulouse', 'Paris15', 'Lyon'];
        return view('campuses', compact('campuses'));
    }

    public function show($ville)
    {
        $adresses = [
            'Aix' => 'Rue de la Couronne',
            'Nantes' => 'Place du Commerce',
            'Rennes' => 'Avenue de la Liberté',
            'Toulouse' => 'Rue de Metz',
            'Paris15' => 'Quai de Grenelle',
            'Lyon' => 'Place Bellecour'
        ];

        return view('campus_detail', [
            'nom' => $ville,
            'adresse' => $adresses[$ville] ?? 'Adresse inconnue'
        ]);
    }
}