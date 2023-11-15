<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Seance;
use Illuminate\Http\Request;

class PointageController extends Controller
{
    public function pointage($code)
    {
        // Récupérer la séance par son code
        $seance = $this->getSeance($code);

        $personnes = [];
        foreach ($seance->presences as $presence) {
            $personnes[] = $presence->personne;
        }

        // Passer la séance et les données associées à la vue
        return view('pointage', [
            'seance' => $seance,
            'groupe' => $seance->groupe,
            'personnes' => $personnes,
            'presences' => $this->presencesForJson($seance),
        ]);
    }

    public function get($code)
    {
        return response()->json(['presences' => $this->presencesForJson($this->getSeance($code))]);
    }

    public function update(Request $request, $code, $personneId)
    {
        $request->validate([
            'statut' => 'required|in:Présent,Absent,Non pointé',
        ]);

        // Trouver la séance par son code
        $seance = $this->getSeance($code);

        // Trouver la présence correspondante
        $presence = Presence::where('seance_id', $seance->id)->where('personne_id', $personneId)->firstOrFail();
        $presence->statut = $request->statut;
        $presence->save();

        $seance = $this->getSeance($code);

        return response()->json(['presences' => $this->presencesForJson($seance)]);
    }

    private function getSeance($code)
    {
        return Seance::with(['groupe', 'presences.personne'])->where('code', $code)->firstOrFail();
    }

    private function presencesForJson($seance)
    {
        $presences = [];
        foreach ($seance->presences as $presence) {
            $presences[$presence->personne->id] = $presence->statut;
        }
        return $presences;
    }
}
