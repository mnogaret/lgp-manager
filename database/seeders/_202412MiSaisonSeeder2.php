<?php

namespace Database\Seeders;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Saison;
use Illuminate\Database\Seeder;

class _202412MiSaisonSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saison = Saison::firstOrCreate(['nom' => '2024 - 2025']); // Vérifie ou crée la saison si elle n'existe pas déjà

        // Créneaux associés aux groupes du dimanche
        $creneau = Creneau::where('saison_id', $saison->id)
            ->where('jour', 'Dimanche')
            ->where('heure_debut', '20:00:00')
            ->where('heure_fin', '21:00:00')
            ->first();

        if (!$creneau)
        {
            throw new \Exception("Créneau adulte du dimanche inexistant");
        }

        // Nouveaux groupes du dimanche
        $adulte_int_dim = Groupe::create([
            'saison_id' => $saison->id,
            'type' => 'Adulte intermédiaire',
            'code' => '2024-adulte-int-dim',
            'nom' => 'Adulte intermédiaire du dimanche',
            'prix' => 255
        ]);

        $adulte_int_dim->creneaux()->attach($creneau->id);
        // Ajouter le reste de tes groupes et créneaux ici si nécessaire
    }
}