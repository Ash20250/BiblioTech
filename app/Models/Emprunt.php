<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    use HasFactory;

protected $fillable = [
    'date_emprunt', 
    'date_retour_prevue', 
    'date_retour_effectif',
    'usager_id', 
    'exemplaire_id',
];

protected $casts = [
    'date_emprunt'         => 'datetime',
    'date_retour_prevue'   => 'datetime',
    'date_retour_effectif' => 'datetime', 
];

    /**
     * Relation : Un emprunt appartient à un usager (User)
     */
    public function usager()
    {
        return $this->belongsTo(User::class, 'usager_id');
    }

    /**
     * Relation : Un emprunt concerne un exemplaire précis
     */
    public function exemplaire()
    {
        return $this->belongsTo(\App\Models\Exemplaire::class, 'exemplaire_id');
    }

    /**
     * Raccourci magique : Permet d'accéder directement au livre depuis l'emprunt
     * Via $emprunt->livre dans ta vue
     */
    public function livre()
    {
        return $this->hasOneThrough(
            Livre::class,      
            \App\Models\Exemplaire::class, 
            'id',              // Clé étrangère sur la table exemplaires...
            'id',              // Clé étrangère sur la table livres...
            'exemplaire_id',   // Clé locale sur la table emprunts...
            'livre_id'         // Clé locale sur la table exemplaires...
        );
    }
}