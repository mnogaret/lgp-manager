<?php

namespace App\Http\Controllers;

use App\Models\Personne;

class FicheController extends Controller
{
    public function show($hash_code)
    {
        // Récupérer l'adhérent en fonction du hash_code
        $adherent = Personne::where('hash_code', $hash_code)->with('adhesions.groupe')->first();

        // Si aucun adhérent n'est trouvé, retourner une erreur 404
        if (!$adherent) {
            return abort(404, 'Adhérent non trouvé');
        }

        // Retourner la vue avec les données de l'adhérent
        return view('fiche.show', compact('adherent'));
    }
}
