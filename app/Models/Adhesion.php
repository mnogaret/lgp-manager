<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adhesion extends Model
{
    protected $table = 'adhesion';
    use HasFactory;

    protected $fillable = ['personne_id', 'date_creation_dossier', 'groupe_id', 'etat'];
}
