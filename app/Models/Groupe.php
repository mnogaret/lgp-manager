<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $table = 'groupe';
    use HasFactory;

    public function creneaux()
    {
        return $this->belongsToMany(Creneau::class, 'groupe_creneau');
    }

    public function seances()
    {
        return $this->hasMany(Seance::class, 'groupe_id');
    }

    public function adhesions()
    {
        return $this->hasMany(Adhesion::class, 'groupe_id');
    }

    public function getInscrits()
    {
        $adhesions = [];
        foreach ($this->adhesions as $adhesion) {
            if (in_array($adhesion->etat, Adhesion::ETAT_INSCRIT)) {
                $adhesions[] = $adhesion;
            }
        }
        return $adhesions;
    }

    public function getRegle()
    {
        $adhesions = [];
        foreach ($this->adhesions as $adhesion) {
            if (in_array($adhesion->etat, Adhesion::ETAT_REGLE)) {
                $adhesions[] = $adhesion;
            }
        }
        return $adhesions;
    }

    public function getListeAttente()
    {
        $adhesions = [];
        foreach ($this->adhesions as $adhesion) {
            if (in_array($adhesion->etat, ['liste d’attente'])) {
                $adhesions[] = $adhesion;
            }
        }
        return $adhesions;
    }

}
