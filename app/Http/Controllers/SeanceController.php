<?php

namespace App\Http\Controllers;

use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\Presence;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeanceController extends Controller
{
    // Afficher le formulaire de création
    public function create($groupeId, $creneauId)
    {
        $groupe = Groupe::findOrFail($groupeId);
        $creneau = Creneau::findOrFail($creneauId);
        return view('seance.create', compact('groupe', 'creneau'));
    }

    // Enregistrer la nouvelle séance
    public function store(Request $request, $groupeId, $creneauId)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $groupeId, $creneauId) {
            $groupe = Groupe::findOrFail($groupeId);
            $creneau = Creneau::findOrFail($creneauId);

            $seance = new Seance();
            $seance->groupe_id = $groupeId;
            $seance->creneau_id = $creneauId;
            $seance->code = Str::random(8);
            $seance->date = $request->date;
            $seance->heure_debut = $creneau->heure_debut;
            $seance->heure_fin = $creneau->heure_fin;
            $seance->save();

            foreach ($groupe->adhesions as $adhesion) {
                if ($adhesion->isInscrit()) {
                    Presence::create([
                        'personne_id' => $adhesion->personne->id,
                        'seance_id' => $seance->id,
                    ]);
                }
            }
        });

        return redirect()->route('groupe.index')->with('success', 'Séance créée avec succès.');
    }
}
