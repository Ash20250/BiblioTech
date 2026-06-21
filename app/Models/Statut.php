<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- AJOUTE ÇA
use Illuminate\Database\Eloquent\Model;

class Statut extends Model
{
    use HasFactory;

    protected $fillable = ['statut']; // On met 'statut' ici aussi
}