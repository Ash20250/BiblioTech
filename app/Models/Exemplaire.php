<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Exemplaire extends Model
{
    use HasFactory; 

    protected $fillable = [
        'mise_en_service', 
        'livre_id', 
        'statut_id', 
        'reserved_by_user_id'
    ];

    public function getIsReservedAttribute()
    {
        return $this->reserved_by_user_id !== null;
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    /**
     * ✅ CORRECTION : Passage en chaîne de caractères pour contourner le bug de détection
     */
    public function emprunts()
    {
        return $this->hasMany('App\Models\Emprunt');
    }

    public function reservataire()
    {
        return $this->belongsTo(User::class, 'reserved_by_user_id');
    }
}