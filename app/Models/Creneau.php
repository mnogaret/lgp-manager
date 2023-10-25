<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creneau extends Model
{
    protected $table = 'creneau';
    use HasFactory;

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'groupe_creneau');
    }
}
