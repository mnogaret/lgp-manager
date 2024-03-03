<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassageDeLame extends Model
{
    protected $table = 'passage_de_lame';
    use HasFactory;

    // Les attributs que vous pouvez assigner de manière massive
    protected $fillable = [
        'personne_id',
        'date',
        'examinateur',
        'niveau',
        'etat',
        'medaille',
        'medaille_remise',
    ];

    // Relation avec la table 'personne'
    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }
}
