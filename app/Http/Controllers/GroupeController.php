<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Creneau;
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
        })->orderBy('nom')->orderBy('prenom')->get();

        $pdf = Pdf::loadView('groupe.pdf', ['groupe' => $groupe, 'adherents' => $adherents]);

        // Définir l'orientation en paysage
        $pdf->setPaper('a4', 'landscape');

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download('liste-' . $groupe->code . '.pdf');
    }

    public function groupes_pdf()
    {
        $impressions = [
            [
                'nom' => 'Baby',
                'groupes' => ['2023-baby-mar', '2023-baby-ven'],
            ],
            [
                'nom' => 'Lame 1',
                'groupes' => ['2023-lame1'],
            ],
            [
                'nom' => 'Lame 2',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 1', 'Lame 2', 'Lame 1/2', null]
            ],
            [
                'nom' => 'Lame 3',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 3', 'Lame 3/4'],
            ],
            [
                'nom' => 'Lame 4',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 4'],
            ],
            [
                'nom' => 'Lame 5',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 5', 'Lame 4/5'],
            ],
            [
                'nom' => 'Lame 6',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 6'],
            ],
            [
                'nom' => 'Lame 7',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 7', 'Lame 7/8'],
            ],
            [
                'nom' => 'Lame 8',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 8'],
            ],
            [
                'nom' => 'Ados',
                'groupes' => ['2023-ados'],
            ],
            [
                'nom' => 'Adultes D&D du mardi',
                'groupes' => ['2023-adulte-deb-mar', '2023-adulte-dan-mar'],
            ],
            [
                'nom' => 'Adultes D&D du vendredi',
                'groupes' => ['2023-adulte-deb-ven', '2023-adulte-dan-ven'],
            ],
            [
                'nom' => 'Adultes D&D du samedi',
                'groupes' => ['2023-adulte-deb-sam', '2023-adulte-dan-sam'],
            ],
            [
                'nom' => 'Adultes I&S du mardi',
                'groupes' => ['2023-adulte-int-mar', '2023-adulte-sau-mar'],
            ],
            [
                'nom' => 'Adultes I&S du vendredi',
                'groupes' => ['2023-adulte-int-ven', '2023-adulte-sau-ven'],
            ],
            [
                'nom' => 'Adultes I&S du samedi',
                'groupes' => ['2023-adulte-int-sam', '2023-adulte-sau-sam'],
            ],
            [
                'nom' => 'PPG du mardi 20h',
                'groupes' => ['2023-ppg-mar-20'],
            ],
            [
                'nom' => 'PPG du mardi 21h',
                'groupes' => ['2023-ppg-mar-21'],
            ],
        ];

        $etats = Adhesion::ETAT_INSCRIT;
        foreach ($impressions as $key => $value) {
            $impressions[$key]['adherents'] = [];
            $impressions[$key]['creneaux'] = [];
            foreach ($impressions[$key]['groupes'] as $groupe_code) {
                $groupe = Groupe::where('code', $groupe_code)->firstOrFail();
                $id = $groupe->id;
                $impressions[$key]['adherents'] = array_merge($impressions[$key]['adherents'], Personne::with(
                    ['adhesions' => function ($query) use ($id, $etats) {
                        $query->where('id', $id)->whereIn('etat', $etats);
                    }]
                )->whereHas('adhesions', function ($query) use ($id, $etats) {
                    $query->where('id', $id)->whereIn('etat', $etats);
                })->orderBy('nom')->orderBy('prenom')->get()->toArray());

                $groupe_codes = $impressions[$key]['groupes'];
                $impressions[$key]['creneaux'] = Creneau::whereHas('groupes', function ($query) use ($groupe_codes) {
                    $query->whereIn('code', $groupe_codes);
                })->get();
            }
        }

        $pdf = Pdf::loadView('groupe.global_pdf', ['impressions' => $impressions]);

        // Définir l'orientation en paysage
        $pdf->setPaper('a4', 'landscape');

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download(date('Ymd') . '-listes.pdf');
    }
}
