<?php

namespace Database\Seeders;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Saison;
use Illuminate\Database\Seeder;

class _202501P1P2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saison = Saison::firstOrCreate(['nom' => '2024 - 2025']); // Vérifie ou crée la saison si elle n'existe pas déjà

        Groupe::create([
            'saison_id' => $saison->id,
            'type' => 'Perfectionnement',
            'code' => '2024-p1',
            'nom' => 'P1 / EDG',
            'prix' => 1150
        ]);

        Groupe::create([
            'saison_id' => $saison->id,
            'type' => 'Perfectionnement',
            'code' => '2024-p2',
            'nom' => 'P2',
            'prix' => 1150
        ]);
    }
}