<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'auteur_id', 'categorie_id'];

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
     * Relation : Un livre possède plusieurs exemplaires physiques
     */
    public function exemplaires()
    {
        return $this->hasMany(Exemplaire::class);
    }
}