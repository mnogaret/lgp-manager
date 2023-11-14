<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adhesion extends Model
{
    protected $table = 'adhesion';
    use HasFactory;

    protected $fillable = ['personne_id', 'date_creation_dossier', 'groupe_id', 'etat'];

    public const ETAT_INSCRIT = ['créé', 'essai', 'complet', 'réglé', 'validé'];
    public const ETAT_REGLE = ['réglé', 'validé'];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

    public function isInscrit()
    {
        return in_array($this->etat, Adhesion::ETAT_INSCRIT);
    }
}
