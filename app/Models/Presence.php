<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $table = 'presence';
    use HasFactory;
    protected $fillable = ['personne_id', 'seance_id', 'statut'];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }
}
