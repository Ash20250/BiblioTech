<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être remplis en masse.
     */
    protected $fillable = ['user_id', 'livre_id'];

    /**
     * Relation inverse : Un favori appartient à un livre.
     */
    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Relation inverse : Un favori appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}