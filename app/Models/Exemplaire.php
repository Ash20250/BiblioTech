<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Exemplaire extends Model
{
    use HasFactory; 

    protected $fillable = ['mise_en_service', 'livre_id', 'statut_id'];

    /**
     * Relation : Un exemplaire appartient à un Livre
     */
    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Relation : Un exemplaire a un Statut (Excellent, Bon, etc.)
     */
    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    /**
     * NOUVEAU - Relation : Un exemplaire peut avoir plusieurs emprunts au cours de sa vie
     * Cette relation permet au contrôleur de vérifier la disponibilité.
     */
    public function emprunts()
    {
        return $this->hasMany(Emprunt::class);
    }
}