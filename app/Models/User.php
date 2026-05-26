<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être remplis massivement.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ajouté ici pour ta gestion d'admin/usager
    ];

    /**
     * Les attributs cachés (pour la sécurité).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversion des types de données.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * RELATION : Les livres favoris de l'utilisateur.
     * C'est une relation "Plusieurs-à-Plusieurs" (Many-to-Many)
     */
    public function favoris()
    {
        // On lie User à Livre via la table pivot 'favoris'
        return $this->belongsToMany(Livre::class, 'favoris')->withTimestamps();
    }

    /**
     * ✅ AJOUT : Relation inverse pour la gestion des prêts
     * Un usager (Client) peut avoir plusieurs fiches d'emprunts historiques ou en cours
     */
    public function emprunts()
    {
        return $this->hasMany(\App\Models\Emprunt::class, 'usager_id');
    }
}