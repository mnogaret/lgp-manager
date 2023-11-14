<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $table = 'seance';
    use HasFactory;
    protected $fillable = ['groupe_id', 'creneau_id', 'code', 'statut', 'date', 'heure_debut', 'heure_fin'];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'seance_id');
    }
}
