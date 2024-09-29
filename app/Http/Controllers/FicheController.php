<?php

namespace App\Http\Controllers;

use App\Models\Personne;

class FicheController extends Controller
{
    public function show($hash_code)
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        // Récupérer l'adhérent en fonction du hash_code
        $adherent = Personne::where('hash_code', $hash_code)
            ->with(['adhesions' => function ($query) use ($saisonId) {
                // Filtrer les adhésions pour n'inclure que celles de la saison sélectionnée
                $query->whereHas('groupe', function ($query) use ($saisonId) {
                    $query->where('saison_id', $saisonId);
                });
            }, 'passages'])
            ->first();

        // Si aucun adhérent n'est trouvé, retourner une erreur 404
        if (!$adherent) {
            return abort(404, 'Adhérent non trouvé');
        }

        // Retourner la vue avec les données de l'adhérent
        return view('fiche.show', compact('adherent'));
    }
}
