<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foyer extends Model
{
    protected $table = 'foyer';
    use HasFactory;

    protected $fillable = ['foyer_id'];

    public function membres()
    {
        return $this->hasMany(Personne::class, 'foyer_id');
    }
}
