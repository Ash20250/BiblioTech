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
        'exemplaire_id'
    ];

    /**
     * Relation vers l'Usager (User)
     */
    public function usager()
    {
        // On lie l'emprunt à l'utilisateur via la colonne usager_id
        return $this->belongsTo(User::class, 'usager_id');
    }

    /**
     * Relation vers l'Exemplaire
     */
    public function exemplaire()
    {
        return $this->belongsTo(Exemplaire::class);
    }
}