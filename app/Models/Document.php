<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'document';
    protected $fillable = ['date', 'type', 'url', 'extra', 'saison_id', 'personne_id'];
    use HasFactory;
}
