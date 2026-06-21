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
        'role', // Géré par ton DatabaseSeeder
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
     * AJOUT : Accesseur pour vérifier si l'utilisateur est admin.
     * Permet d'utiliser @if(Auth::user()->is_admin) dans les vues Blade.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'bibliothecaire';
    }

    /**
     * RELATION : Les livres favoris de l'utilisateur.
     */
    public function favoris()
    {
        return $this->belongsToMany(Livre::class, 'favoris')->withTimestamps();
    }

    /**
     * RELATION : Les fiches d'emprunts de l'utilisateur.
     */
    public function emprunts()
    {
        return $this->hasMany(\App\Models\Emprunt::class, 'usager_id');
    }
}