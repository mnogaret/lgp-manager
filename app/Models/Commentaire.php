<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaire';
    use HasFactory;
    protected $fillable = ['user_id', 'type', 'foyer_id', 'personne_id', 'commentaire'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

}
