<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use Barryvdh\DomPDF\Facade\Pdf;

class BadgesController extends Controller
{
    public function pdf()
    {
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupes = [
            '2024-baby',
            '2024-initiation1',
            '2024-initiation2',
            '2024-ados',
        ];

        $adherents = Personne::whereHas('adhesions', function($query) use ($groupes, $saisonId) {
            $query->whereHas('groupe', function($query) use ($groupes, $saisonId) {
                // Filtrer selon les groupes et la saison active
                $query->whereIn('code', $groupes)
                      ->where('saison_id', $saisonId); // Filtrer par la saison active
            })
            ->where('etat', 'validé'); // Filtrer par l'état de l'adhésion (ici 'validée')
        })->with(['adhesions' => function($query) use ($saisonId) {
            // Ne charger que les adhésions de la saison active
            $query->whereHas('groupe', function($query) use ($saisonId) {
                $query->where('saison_id', $saisonId);
            });
        }])->get();

        // TODO ajouter la photo

        return Pdf::loadView('badges/pdf', ['adherents' => $adherents])->download(date('Ymd') . '-badges.pdf');
    }
}
