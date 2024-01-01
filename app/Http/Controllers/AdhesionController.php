<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdhesionController extends Controller
{
    public function changeEtat(Request $request, Adhesion $adhesion)
    {
        $validatedData = $request->validate([
            'etat' => 'required|in:annulé,validé,réglé,complet,essai,liste d’attente,créé',
        ]);

        if ($adhesion->etat !== $validatedData['etat']) {

            $adhesion->update([
                'etat' => $validatedData['etat'],
            ]);

            Commentaire::create([
                'user_id' => Auth::user()->id,
                'type' => 'Edit adhesion',
                'foyer_id' => $adhesion->personne->foyer_id,
                'personne_id' => $adhesion->personne->id,
                'commentaire' => $adhesion->groupe->code . " => " . $validatedData['etat'],
            ]);
        }
        // Rediriger ou retourner une réponse
        return back()->with('success', 'État de l’adhésion mis à jour avec succès.');
    }
}
