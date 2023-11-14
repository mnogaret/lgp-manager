<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $table = 'seance';
    use HasFactory;
    protected $fillable = ['creneau_id', 'code', 'statut', 'date', 'heure_debut', 'heure_fin'];
}
