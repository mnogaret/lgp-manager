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
        $groupes = Groupe::with(['adhesions.personne', 'seances' => function ($query) {
            $query->where('statut', 'Ouvert');
        }])->get();

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

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download('liste-' . $groupe->code . '.pdf');
    }

    public function groupes_pdf()
    {
        $impression_defs = [
            [
                'nom' => 'Baby',
                'groupes' => ['2023-baby-mar', '2023-baby-ven'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 1',
                'groupes' => ['2023-lame1'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 2',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 1', 'Lame 2'],
                'niveau_null' => true,
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 3',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 3'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 4',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 4'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 5',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 5'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 6',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 6'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 7',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 7'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 8',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 8'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Ados',
                'groupes' => ['2023-ados'],
                'niveaux' => true,
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
        $impressions = [];
        for ($i = 0; $i < count($impression_defs); $i++) {
            $impression = $impression_defs[$i];


            $groupe_codes = $impression['groupes'];

            $impression['creneaux'] = Creneau::whereHas('groupes', function ($query) use ($groupe_codes) {
                $query->whereIn('code', $groupe_codes);
            })->get();

            $impression['adherents'] = [];
            foreach ($groupe_codes as $groupe_code) {
                $groupe = Groupe::where('code', $groupe_code)->firstOrFail();
                $id = $groupe->id;

                $q = Personne::with(
                    ['adhesions' => function ($query) use ($id, $etats) {
                        $query->where('groupe_id', $id)->whereIn('etat', $etats);
                    }]
                )->whereHas('adhesions', function ($query) use ($id, $etats) {
                    $query->where('groupe_id', $id)->whereIn('etat', $etats);
                });
                if (isset($impression['niveau'])) {
                    $q = $q->where(function ($query) use ($impression) {
                        $query->where(function ($subquery) use ($impression) {
                            if (isset($impression['niveau']) && count($impression['niveau']) > 0) {
                                $subquery->whereIn('niveau', $impression['niveau']);
                            }
                        });

                        if (isset($impression['niveau_null']) && $impression['niveau_null']) {
                            $query->orWhereNull('niveau');
                        }
                    });
                }
                $adherents = $q->orderBy('nom')->orderBy('prenom')->get();

                foreach ($adherents as $adherent) {
                    $impression['adherents'][] = $adherent;
                }
            }
            if (count($impression['adherents']) > 0) {
                $impressions[] = $impression;
            }
        }

        $pdf = Pdf::loadView('groupe.global_pdf', ['impressions' => $impressions]);

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download(date('Ymd') . '-listes.pdf');
    }

    public function lames_pdf()
    {
        $impression_defs = [
            [
                'nom' => 'Patin Rouge',
                'groupes' => ['2023-baby-mar', '2023-baby-ven'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 1',
                'groupes' => ['2023-lame1'],
                'niveaux' => true,
                'to' => 'J',
            ],
            [
                'nom' => 'Lame 1',
                'groupes' => ['2023-lame1'],
                'niveaux' => true,
                'from' => 'J',
                'to' => 'P',
            ],
            [
                'nom' => 'Lame 1',
                'groupes' => ['2023-lame1'],
                'niveaux' => true,
                'from' => 'P',
            ],
            [
                'nom' => 'Lame 2',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 1', 'Lame 2'],
                'niveau_null' => true,
                'niveaux' => true,
                'to' => 'D',
            ],
            [
                'nom' => 'Lame 2',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 1', 'Lame 2'],
                'niveau_null' => true,
                'niveaux' => true,
                'from' => 'D',
                'to' => 'L',
            ],
            [
                'nom' => 'Lame 2',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 1', 'Lame 2'],
                'niveau_null' => true,
                'niveaux' => true,
                'from' => 'L',
            ],
            [
                'nom' => 'Lame 3',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 3'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 4',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 4'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 5',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 5'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 6',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 6'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 7',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 7'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Lame 8',
                'groupes' => ['2023-lame2+'],
                'niveau' => ['Lame 8'],
                'niveaux' => true,
            ]
        ];

        $etats = Adhesion::ETAT_INSCRIT;
        $impressions = [];
        for ($i = 0; $i < count($impression_defs); $i++) {
            $impression = $impression_defs[$i];

            $groupe_codes = $impression['groupes'];

            $impression['creneaux'] = Creneau::whereHas('groupes', function ($query) use ($groupe_codes) {
                $query->whereIn('code', $groupe_codes);
            })->get();

            $impression['adherents'] = [];
            foreach ($groupe_codes as $groupe_code) {
                $groupe = Groupe::where('code', $groupe_code)->firstOrFail();
                $id = $groupe->id;

                $q = Personne::with(
                    ['adhesions' => function ($query) use ($id, $etats) {
                        $query->where('groupe_id', $id)->whereIn('etat', $etats);
                    }]
                )->whereHas('adhesions', function ($query) use ($id, $etats) {
                    $query->where('groupe_id', $id)->whereIn('etat', $etats);
                });
                if (isset($impression['niveau'])) {
                    $q = $q->where(function ($query) use ($impression) {
                        $query->where(function ($subquery) use ($impression) {
                            if (isset($impression['niveau']) && count($impression['niveau']) > 0) {
                                $subquery->whereIn('niveau', $impression['niveau']);
                            }
                        });
                        if (isset($impression['niveau_null']) && $impression['niveau_null']) {
                            $query->orWhereNull('niveau');
                        }
                    });
                }
                if (isset($impression['from']))
                {
                    $from = $impression['from'];
                    $q = $q->where('nom', '>=', $from);
                }
                if (isset($impression['to']))
                {
                    $to = $impression['to'];
                    $q = $q->where('nom', '<', $to);
                }
                $adherents = $q->orderBy('nom')->orderBy('prenom')->get();

                foreach ($adherents as $adherent) {
                    $impression['adherents'][] = $adherent;
                }
            }
            if (count($impression['adherents']) > 0) {
                $impressions[] = $impression;
            }
        }

        $pdf = Pdf::loadView('groupe.lames_pdf', ['impressions' => $impressions]);

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download(date('Ymd') . '-lames.pdf');
    }
}
