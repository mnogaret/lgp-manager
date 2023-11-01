<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foyer extends Model
{
    protected $table = 'foyer';
    use HasFactory;

    protected $fillable = ['foyer_id', 'montant_total', 'montant_regle'];

    public function membres()
    {
        return $this->hasMany(Personne::class, 'foyer_id');
    }

    public function reglements()
    {
        return $this->hasMany(Reglement::class, 'foyer_id');
    }

    public function montant_total_reglements()
    {
        $montant_total = 0;
        foreach ($this->reglements as $reglement) {
            $montant_total += $reglement->montant;
        }
        return $montant_total;
    }

    public function montant_total_cotisations()
    {
        $montant_total = 0;
        $adherent_count = 0;
        $multi_groupe_types = ['Adulte débutant', 'Adulte intermédiaire', 'Adulte danseur', 'Adulte sauteur'];

        foreach ($this->membres as $membre) {
            $is_multi_groupe = false;
            foreach ($membre->adhesions as $adhesion) {
                $montant_total += $adhesion->groupe->prix;
                if (in_array($adhesion->groupe->type, $multi_groupe_types)) {
                    if ($is_multi_groupe) {
                        $montant_total -= 30;
                    }
                    $is_multi_groupe = true;
                }
            }
            if (count($membre->adhesions) > 0) {
                $adherent_count++;
            }
        }
        if ($adherent_count > 1) {
            $montant_total -= ($adherent_count - 1) * 25;
        }
        return $montant_total;
    }
}
