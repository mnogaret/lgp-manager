<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'email1', 'email2', 'telephone1', 'telephone2', 'adresse_postale', 'code_postal', 'ville', 'date_naissance', 'sexe', 'nationalite', 'ville_naissance', 'numero_licence', 'chef_de_foyer_id'];

    public function chefDeFoyer()
    {
        return $this->belongsTo(Personne::class, 'chef_de_foyer_id');
    }

    public function membresDuFoyer()
    {
        return $this->hasMany(Personne::class, 'chef_de_foyer_id');
    }
}
