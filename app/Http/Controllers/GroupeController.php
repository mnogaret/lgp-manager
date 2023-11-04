<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Groupe;
use App\Models\Personne;
use Barryvdh\DomPDF\Facade\Pdf;

class GroupeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupes = Groupe::with('adhesions.personne')->get();
        return view('groupe.index', ['groupes' => $groupes]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $groupe = Groupe::findOrFail($id);

        $etats = Adhesion::ETAT_INSCRIT;
        $adherents = Personne::with(
            ['adhesions' => function ($query) use ($id, $etats) {
                $query->where('groupe_id', $id)->whereIn('etat', $etats);
            }]
        )->whereHas('adhesions', function ($query) use ($id, $etats) {
            $query->where('groupe_id', $id)->whereIn('etat', $etats);
        })->get();

        return view('groupe.show', ['groupe' => $groupe, 'adherents' => $adherents]);
    }

    public function pdf(string $id)
    {
        $groupe = Groupe::findOrFail($id);

        $etats = Adhesion::ETAT_INSCRIT;
        $adherents = Personne::with(
            ['adhesions' => function ($query) use ($id, $etats) {
                $query->where('groupe_id', $id)->whereIn('etat', $etats);
            }]
        )->whereHas('adhesions', function ($query) use ($id, $etats) {
            $query->where('groupe_id', $id)->whereIn('etat', $etats);
        })->get();

        $pdf = \PDF::loadView('groupe.pdf', ['groupe' => $groupe, 'adherents' => $adherents]);

        // Définir l'orientation en paysage
        $pdf->setPaper('a4', 'landscape');

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download('liste-' . $groupe->code . '.pdf');
    }
}
