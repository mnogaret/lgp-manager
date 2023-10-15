<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    use HasFactory;

    public function chefDeFoyer()
    {
        return $this->belongsTo(Personne::class, 'chef_de_foyer_id');
    }

    public function membresDuFoyer()
    {
        return $this->hasMany(Personne::class, 'chef_de_foyer_id');
    }
}
