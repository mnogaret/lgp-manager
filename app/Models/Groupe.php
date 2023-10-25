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
}
