<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'auteur_id', 'categorie_id', 'isbn'];

    /**
     * Relation : Un livre appartient à un Auteur
     */
    public function auteur()
    {
        return $this->belongsTo(Auteur::class);
    }

    /**
     * Relation : Un livre appartient à une Catégorie
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Un livre possède plusieurs exemplaires physiques
     */
    public function exemplaires()
    {
        return $this->hasMany(\App\Models\Exemplaire::class, 'livre_id');
    }

    /**
     * ✅ AJOUT : Relation pour les "Coups de cœur"
     * Permet de vérifier facilement si un livre est favori pour un utilisateur
     */
    public function favoris()
    {
        return $this->hasMany(\App\Models\Favori::class, 'livre_id');
    }
}