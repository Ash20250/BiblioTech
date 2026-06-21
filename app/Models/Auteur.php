<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- AJOUTE ÇA
use Illuminate\Database\Eloquent\Model;

class Auteur extends Model
{
    use HasFactory; // <--- AJOUTE ÇA

    protected $fillable = ['nom'];
}