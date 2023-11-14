<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use Illuminate\Http\Request;

class PointageController extends Controller
{
    public function pointage($code)
    {
        // Récupérer la séance par son code
        $seance = Seance::with(['groupe', 'presences.personne'])
            ->where('code', $code)
            ->firstOrFail();

        // Passer la séance et les données associées à la vue
        return view('pointage', [
            'seance' => $seance,
            'groupe' => $seance->groupe,
            'presences' => $seance->presences
        ]);
    }
}
