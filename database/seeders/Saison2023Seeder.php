<?php

namespace Database\Seeders;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Saison;
use App\Models\User;
use Illuminate\Database\Seeder;

class Saison2023Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saison = Saison::create(['nom' => '2023 - 2024']);

        $baby_mar =       Groupe::create(['saison_id' => $saison->id, 'type' => 'Baby',                 'code' => '2023-baby-mar',       'nom' => 'Baby du mardi',                    'prix' => 335]);
        $baby_ven =       Groupe::create(['saison_id' => $saison->id, 'type' => 'Baby',                 'code' => '2023-baby-ven',       'nom' => 'Baby du vendredi',                 'prix' => 335]);
        $lame1 =          Groupe::create(['saison_id' => $saison->id, 'type' => 'Lame 1',               'code' => '2023-lame1',          'nom' => 'Lame 1',                           'prix' => 335]);
        $lame2 =          Groupe::create(['saison_id' => $saison->id, 'type' => 'Lame 2+',              'code' => '2023-lame2+',         'nom' => 'Lames 2 à 8',                      'prix' => 335]);
        $ados =           Groupe::create(['saison_id' => $saison->id, 'type' => 'Ados',                 'code' => '2023-ados',           'nom' => 'Ados',                             'prix' => 335]);
        $adulte_deb_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2023-adulte-deb-mar', 'nom' => 'Adulte débutant du mardi',         'prix' => 240]);
        $adulte_deb_ven = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2023-adulte-deb-ven', 'nom' => 'Adulte débutant du vendredi',      'prix' => 240]);
        $adulte_deb_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2023-adulte-deb-sam', 'nom' => 'Adulte débutant de samedi',        'prix' => 240]);
        $adulte_int_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte intermédiaire', 'code' => '2023-adulte-int-mar', 'nom' => 'Adulte intermédiaire du mardi',    'prix' => 240]);
        $adulte_int_ven = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte intermédiaire', 'code' => '2023-adulte-int-ven', 'nom' => 'Adulte intermédiaire du vendredi', 'prix' => 240]);
        $adulte_int_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte intermédiaire', 'code' => '2023-adulte-int-sam', 'nom' => 'Adulte intermédiaire de samedi',   'prix' => 240]);
        $adulte_dan_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte danseur',       'code' => '2023-adulte-dan-mar', 'nom' => 'Adulte danseur du mardi',          'prix' => 240]);
        $adulte_dan_ven = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte danseur',       'code' => '2023-adulte-dan-ven', 'nom' => 'Adulte danseur du vendredi',       'prix' => 240]);
        $adulte_dan_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte danseur',       'code' => '2023-adulte-dan-sam', 'nom' => 'Adulte danseur du samedi',         'prix' => 240]);
        $adulte_sau_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte sauteur',       'code' => '2023-adulte-sau-mar', 'nom' => 'Adulte sauteur du mardi',          'prix' => 240]);
        $adulte_sau_ven = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte sauteur',       'code' => '2023-adulte-sau-ven', 'nom' => 'Adulte sauteur du vendredi',       'prix' => 240]);
        $adulte_sau_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte sauteur',       'code' => '2023-adulte-sau-sam', 'nom' => 'Adulte sauteur du samedi',         'prix' => 240]);
        $ppg_mar_20 =     Groupe::create(['saison_id' => $saison->id, 'type' => 'PPG',                  'code' => '2023-ppg-mar-20',     'nom' => 'PPG du mardi 20h',                 'prix' => 200]);
        $ppg_mar_21 =     Groupe::create(['saison_id' => $saison->id, 'type' => 'PPG',                  'code' => '2023-ppg-mar-21',     'nom' => 'PPG du mardi 21h',                 'prix' => 200]);

        // Créer des créneaux pour cette saison
        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '18:15:00', 'heure_fin' => '19:00:00', ]);
        $baby_mar->creneaux()->attach($creneau->id);
        $lame2->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '20:00:00', 'heure_fin' => '21:00:00', ]);
        $ppg_mar_20->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '21:00:00', 'heure_fin' => '22:00:00', ]);
        $adulte_int_mar->creneaux()->attach($creneau->id);
        $adulte_sau_mar->creneaux()->attach($creneau->id);
        $ppg_mar_21->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '22:00:00', 'heure_fin' => '23:00:00', ]);
        $adulte_deb_mar->creneaux()->attach($creneau->id);
        $adulte_dan_mar->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mercredi', 'heure_debut' => '16:45:00', 'heure_fin' => '18:15:00', ]);
        $ados->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mercredi', 'heure_debut' => '18:15:00', 'heure_fin' => '19:00:00', ]);
        $lame1->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Vendredi', 'heure_debut' => '18:15:00', 'heure_fin' => '19:00:00', ]);
        $baby_ven->creneaux()->attach($creneau->id);
        $lame2->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Vendredi', 'heure_debut' => '19:15:00', 'heure_fin' => '20:15:00', ]);
        $adulte_deb_ven->creneaux()->attach($creneau->id);
        $adulte_dan_ven->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Vendredi', 'heure_debut' => '19:45:00', 'heure_fin' => '20:45:00', ]);
        $adulte_int_ven->creneaux()->attach($creneau->id);
        $adulte_sau_ven->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '10:30:00', 'heure_fin' => '11:15:00', ]);
        $lame1->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '20:15:00', 'heure_fin' => '21:15:00', ]);
        $adulte_deb_sam->creneaux()->attach($creneau->id);
        $adulte_dan_sam->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '21:00:00', 'heure_fin' => '22:00:00', ]);
        $adulte_int_sam->creneaux()->attach($creneau->id);
        $adulte_sau_sam->creneaux()->attach($creneau->id);

        User::create([ 'email' => 'mnogaret@lyonglacepatinage.fr' ]);
    }
}
