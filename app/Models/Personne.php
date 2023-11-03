<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    use HasFactory;
    protected $fillable = ['nom', 'prenom', 'email1', 'email2', 'telephone1', 'telephone2', 'adresse_postale', 'code_postal', 'ville', 'date_naissance', 'sexe', 'nationalite', 'ville_naissance', 'date_certificat_medical', 'nom_assurance', 'numero_assurance', 'droit_image', 'numero_licence', 'niveau', 'foyer_id'];

    public function adhesions()
    {
        return $this->hasMany(Adhesion::class, 'personne_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'personne_id');
    }

    public function foyer()
    {
        return $this->belongsTo(Foyer::class);
    }

    public function getAge()
    {
        // Obtenez la date de naissance de la personne
        $dateNaissance = $this->date_naissance;

        // Vérifiez si la date de naissance n'est pas nulle
        if ($dateNaissance) {
            // Convertissez la date de naissance en objet Carbon
            $dateNaissance = Carbon::parse($dateNaissance);

            // Obtenez la date actuelle
            $premierJuillet = Carbon::parse('2023-07-01');

            // Calculez la différence en années
            $age = $dateNaissance->diffInYears($premierJuillet);

            return $age;
        }

        // Si la date de naissance est nulle, retournez 0 ou une valeur par défaut
        return null; // Vous pouvez également retourner null ou une valeur par défaut selon vos besoins.
    }
}
