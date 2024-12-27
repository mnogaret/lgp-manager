<?php

namespace Database\Seeders;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Saison;
use Illuminate\Database\Seeder;

class _202412MiSaisonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saison = Saison::firstOrCreate(['nom' => '2024 - 2025']); // Vérifie ou crée la saison si elle n'existe pas déjà

        // Nouveaux groupes du dimanche
        $adulte_deb_dim1 = Groupe::create([
            'saison_id' => $saison->id,
            'type' => 'Adulte débutant',
            'code' => '2024-adulte-deb-dim1',
            'nom' => 'Adulte débutant du dimanche (1er créneau)',
            'prix' => 255
        ]);

        $adulte_deb_dim2 = Groupe::create([
            'saison_id' => $saison->id,
            'type' => 'Adulte débutant',
            'code' => '2024-adulte-deb-dim2',
            'nom' => 'Adulte débutant du dimanche (2ème créneau)',
            'prix' => 255
        ]);

        // Créneaux associés aux groupes du dimanche
        $creneau1 = Creneau::create([
            'saison_id' => $saison->id,
            'jour' => 'Dimanche',
            'heure_debut' => '20:00:00',
            'heure_fin' => '21:00:00',
        ]);
        $adulte_deb_dim1->creneaux()->attach($creneau1->id);

        $creneau2 = Creneau::create([
            'saison_id' => $saison->id,
            'jour' => 'Dimanche',
            'heure_debut' => '21:00:00',
            'heure_fin' => '22:00:00',
        ]);
        $adulte_deb_dim2->creneaux()->attach($creneau2->id);

        // Ajouter le reste de tes groupes et créneaux ici si nécessaire
    }
}