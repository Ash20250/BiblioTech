<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Exemplaire extends Model
{
    use HasFactory; 

    // Les champs que l'on autorise à remplir via Exemplaire::create()
    protected $fillable = ['mise_en_service', 'livre_id', 'statut_id'];

    /**
     * Relation : Un exemplaire appartient à un Livre
     */
    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Relation : Un exemplaire a un Statut (Ex: 1 pour Disponible)
     */
    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    /**
     * Relation : Un exemplaire peut avoir plusieurs emprunts
     * Utilisé dans le catalogue pour vérifier si le dernier emprunt est rendu.
     */
    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }
}