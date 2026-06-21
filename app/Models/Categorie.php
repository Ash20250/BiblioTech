<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- Vérifie cette ligne
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory; // <--- Et celle-ci

    protected $fillable = ['nom'];
}