<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'email1', 'email2', 'telephone1', 'telephone2', 'adresse_postale', 'code_postal', 'ville', 'date_naissance', 'sexe', 'nationalite', 'ville_naissance', 'numero_licence', 'foyer_id'];

    public function adhesions()
    {
        return $this->hasMany(Adhesion::class, 'personne_id');
    }
}
