<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Creneau;
use App\Models\Groupe;
use App\Models\PassageDeLame;
use App\Models\Personne;
use App\Tools\PassageDeLameImporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BadgesController extends Controller
{
    public function pdf()
    {
        $groupes = [
            '2024-baby',
            '2024-initiation1',
            '2024-initiation2',
            '2024-ados',
        ];

        // Récupérer les adhérents qui sont dans les groupes spécifiés et dont l'adhésion est validée
        $adherents = Personne::whereHas('adhesions', function($query) use ($groupes) {
            $query->whereHas('groupe', function($query) use ($groupes) {
                // Filtrer selon les groupes
                $query->whereIn('code', $groupes);
            })
            ->where('etat', 'validé'); // Filtrer par l'état de l'adhésion (ici 'validée')
        })->with('adhesions.groupe')->get();

        return Pdf::loadView('badges/pdf', ['adherents' => $adherents])->download(date('Ymd') . '-badges.pdf');
    }
}
