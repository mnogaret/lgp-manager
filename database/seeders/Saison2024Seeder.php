<?php

namespace Database\Seeders;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Saison;
use App\Models\User;
use Illuminate\Database\Seeder;

class Saison2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saison = Saison::create(['nom' => '2024 - 2025']);

        $baby =           Groupe::create(['saison_id' => $saison->id, 'type' => 'Baby',                 'code' => '2024-baby',           'nom' => 'Baby',                           'prix' => 255]);
        $initiation1 =    Groupe::create(['saison_id' => $saison->id, 'type' => 'Initiation 1',         'code' => '2024-initiation1',    'nom' => 'Initiation 1',                   'prix' => 345]);
        $initiation2 =    Groupe::create(['saison_id' => $saison->id, 'type' => 'Initiation 2',         'code' => '2024-initiation2',    'nom' => 'Initiation 2',                   'prix' => 345]);
        $ados =           Groupe::create(['saison_id' => $saison->id, 'type' => 'Ados',                 'code' => '2024-ados',           'nom' => 'Ados',                           'prix' => 345]);

        $adulte_deb_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2024-adulte-deb-mar', 'nom' => 'Adulte débutant du mardi',       'prix' => 255]);
        $adulte_deb_mer = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2024-adulte-deb-mer', 'nom' => 'Adulte débutant du mercredi',    'prix' => 255]);
        $adulte_deb_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte débutant',      'code' => '2024-adulte-deb-sam', 'nom' => 'Adulte débutant de samedi',      'prix' => 255]);

        $adulte_int_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte intermédiaire', 'code' => '2023-adulte-int-mar', 'nom' => 'Adulte intermédiaire du mardi',  'prix' => 255]);
        $adulte_int_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte intermédiaire', 'code' => '2023-adulte-int-sam', 'nom' => 'Adulte intermédiaire de samedi', 'prix' => 255]);

        $adulte_dan_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte danseur',       'code' => '2023-adulte-dan-mar', 'nom' => 'Adulte danseur du mardi',        'prix' => 255]);
        $adulte_dan_mer = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte danseur',       'code' => '2023-adulte-dan-mer', 'nom' => 'Adulte danseur du mercredi',     'prix' => 255]);

        $adulte_sau_mar = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte sauteur',       'code' => '2023-adulte-sau-mar', 'nom' => 'Adulte sauteur du mardi',        'prix' => 255]);
        $adulte_sau_sam = Groupe::create(['saison_id' => $saison->id, 'type' => 'Adulte sauteur',       'code' => '2023-adulte-sau-sam', 'nom' => 'Adulte sauteur du samedi',       'prix' => 255]);

        $ppg_mar1 =       Groupe::create(['saison_id' => $saison->id, 'type' => 'PPG',                  'code' => '2023-ppg-mar-1',      'nom' => 'PPG du mardi 1',                 'prix' => 210]);
        $ppg_mar2 =       Groupe::create(['saison_id' => $saison->id, 'type' => 'PPG',                  'code' => '2023-ppg-mar-2',      'nom' => 'PPG du mardi 2',                 'prix' => 210]);
        $ppg_mer =        Groupe::create(['saison_id' => $saison->id, 'type' => 'PPG',                  'code' => '2023-ppg-mer',        'nom' => 'PPG du mercredi',                'prix' => 210]);

        // Créer des créneaux pour cette saison
        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '18:15:00', 'heure_fin' => '19:00:00', ]);
        $baby->creneaux()->attach($creneau->id);
        $initiation2->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '19:45:00', 'heure_fin' => '20:45:00', ]);
        $ppg_mar1->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '20:45:00', 'heure_fin' => '21:45:00', ]);
        $ppg_mar2->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '21:00:00', 'heure_fin' => '22:00:00', ]);
        $adulte_int_mar->creneaux()->attach($creneau->id);
        $adulte_sau_mar->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mardi',    'heure_debut' => '22:00:00', 'heure_fin' => '23:00:00', ]);
        $adulte_deb_mar->creneaux()->attach($creneau->id);
        $adulte_dan_mar->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mercredi', 'heure_debut' => '16:45:00', 'heure_fin' => '18:15:00', ]);
        $ados->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mercredi', 'heure_debut' => '20:45:00', 'heure_fin' => '21:45:00', ]);
        $ppg_mer->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Mercredi', 'heure_debut' => '22:00:00', 'heure_fin' => '23:00:00', ]);
        $adulte_deb_mer->creneaux()->attach($creneau->id);
        $adulte_dan_mer->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Vendredi', 'heure_debut' => '18:15:00', 'heure_fin' => '19:00:00', ]);
        $initiation1->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '10:15:00', 'heure_fin' => '11:00:00', ]);
        $initiation1->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '11:00:00', 'heure_fin' => '11:45:00', ]);
        $baby->creneaux()->attach($creneau->id);
        $initiation2->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '20:15:00', 'heure_fin' => '21:15:00', ]);
        $adulte_deb_sam->creneaux()->attach($creneau->id);
        $adulte_int_sam->creneaux()->attach($creneau->id);

        $creneau = Creneau::create(['saison_id' => $saison->id, 'jour' => 'Samedi',   'heure_debut' => '21:00:00', 'heure_fin' => '22:00:00', ]);
        $adulte_sau_sam->creneaux()->attach($creneau->id);
    }
}
