<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $table = 'reglement';
    use HasFactory;
    protected $fillable = ['type', 'date', 'montant', 'code', 'depose', 'acquitte', 'foyer_id', 'saison_id'];
}
