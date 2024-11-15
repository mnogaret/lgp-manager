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
        $saisonId = session('saison_id');
        if (!$saisonId) {
            return redirect()->route('welcome')->withErrors('Aucune saison sélectionnée');
        }

        $groupes = Groupe::with(['adhesions.personne', 'seances' => function ($query) {
            $query->where('statut', 'Ouvert');
        }])->where('saison_id', $saisonId)->get();

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
                'groupes' => ['2024-baby'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Initiation 1',
                'groupes' => ['2024-initiation1'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Initiation 2',
                'groupes' => ['2024-initiation2'],
//                'niveau' => ['Lame 1', 'Lame 2'],
//                'niveau_null' => true,
                'niveaux' => true,
            ],
            [
                'nom' => 'Ados',
                'groupes' => ['2024-ados'],
                'niveaux' => true,
            ],
            [
                'nom' => 'Adultes D&D du mardi',
                'groupes' => ['2024-adulte-deb-mar', '2024-adulte-dan-mar'],
            ],
            [
                'nom' => 'Adultes D&D du mercredi',
                'groupes' => ['2024-adulte-deb-mer', '2024-adulte-dan-mer'],
            ],
            [
                'nom' => 'Adultes D&I du samedi',
                'groupes' => ['2024-adulte-deb-sam', '2024-adulte-int-sam'],
            ],
            [
                'nom' => 'Adultes I&S du mardi',
                'groupes' => ['2024-adulte-int-mar', '2024-adulte-sau-mar'],
            ],
            [
                'nom' => 'Adultes Sauteur du samedi',
                'groupes' => ['2024-adulte-sau-sam'],
            ],
            [
                'nom' => 'PPG du mardi 19h45',
                'groupes' => ['2024-ppg-mar-1'],
            ],
            [
                'nom' => 'PPG du mardi 20h45',
                'groupes' => ['2024-ppg-mar-2'],
            ],
            [
                'nom' => 'PPG du mercredi',
                'groupes' => ['2024-ppg-mer'],
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
/*        $impression_defs = [
            [
                'groupe' => 'Adulte danseur du mardi',
                'niveau' => 'Lame 8',
                'niveau_null' => true,
                'groupes' => ['2023-adulte-dan-mar'],
            ]];*/
        $impression_defs = [
            [
                'groupe' => 'Baby',
                'niveau' => 'Patin Bleu',
                'niveau_null' => true,
                'groupes' => ['2024-baby'],
            ],
            [
                'groupe' => 'Baby',
                'niveau' => 'Patin Rouge',
                'groupes' => ['2024-baby'],
            ],
            [
                'groupe' => 'Initiation 1',
                'niveau' => 'Patin Bleu',
                'niveau_null' => true,
                'groupes' => ['2024-initiation1'],
                'to' => 'G',
            ],
            [
                'groupe' => 'Initiation 1',
                'niveau' => 'Patin Bleu',
                'niveau_null' => true,
                'groupes' => ['2024-initiation1'],
                'from' => 'G',
            ],
            [
                'groupe' => 'Initiation 1',
                'niveau' => 'Patin Rouge',
                'groupes' => ['2024-initiation1'],
                'to' => 'G',
            ],
            [
                'groupe' => 'Initiation 1',
                'niveau' => 'Patin Rouge',
                'groupes' => ['2024-initiation1'],
                'from' => 'G',
            ],
            [
                'groupe' => 'Initiation 1',
                'niveau' => 'Lame 1',
                'groupes' => ['2024-initiation1'],
            ],
            [
                'groupe' => 'Initiation 2',
                'niveau' => 'Lame 2',
                'niveau_null' => true,
                'groupes' => ['2024-initiation2'],
                'to' => 'L',
            ],
            [
                'groupe' => 'Initiation 2',
                'niveau' => 'Lame 2',
                'niveau_null' => true,
                'groupes' => ['2024-initiation2'],
                'from' => 'L',
            ],
            [
                'groupe' => 'Initiation 2',
                'niveau' => 'Lame 3',
                'groupes' => ['2024-initiation2'],
            ],
            [
                'groupe' => 'Initiation 2',
                'niveau' => 'Lame 4',
                'groupes' => ['2024-initiation2'],
            ],
            [
                'groupe' => 'Initiation 2',
                'niveau' => 'Lame 5',
                'groupes' => ['2024-initiation2'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 1',
                'niveau_null' => true,
                'groupes' => ['2024-ados'],
                'to' => 'D',
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 1',
                'niveau_null' => true,
                'groupes' => ['2024-ados'],
                'from' => 'D',
                'to' => 'M',
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 1',
                'niveau_null' => true,
                'groupes' => ['2024-ados'],
                'from' => 'M',
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 2',
                'groupes' => ['2024-ados'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 3',
                'groupes' => ['2024-ados'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 4',
                'groupes' => ['2024-ados'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 5',
                'groupes' => ['2024-ados'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 6',
                'groupes' => ['2024-ados'],
            ],
            [
                'groupe' => 'Ados',
                'niveau' => 'Lame 7',
                'groupes' => ['2024-ados'],
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
                            $subquery->where('niveau', $impression['niveau']);
                        });
                        if (isset($impression['niveau_null']) && $impression['niveau_null']) {
//                            $query->orWhereNull('niveau')->orWhere('niveau', 'Patin Bleu');
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

        $impressions[] = [ 'niveau' => 'Patin Bleu' ];
        $impressions[] = [ 'niveau' => 'Patin Rouge' ];
        $impressions[] = [ 'niveau' => 'Lame 1' ];
        $impressions[] = [ 'niveau' => 'Lame 2' ];
        $impressions[] = [ 'niveau' => 'Lame 3' ];
        $impressions[] = [ 'niveau' => 'Lame 4' ];
        $impressions[] = [ 'niveau' => 'Lame 5' ];
        $impressions[] = [ 'niveau' => 'Lame 6' ];
        $impressions[] = [ 'niveau' => 'Lame 7' ];

        $pdf = Pdf::loadView('groupe.lames_pdf', ['impressions' => $impressions]);

        // Définir des marges personnalisées (si vous avez besoin de valeurs spécifiques)
        // Les valeurs des marges sont en pouces par défaut. [left, top, right, bottom]
        $pdf->setOptions(['margin-left' => '5', 'margin-right' => '5', 'margin-top' => '5', 'margin-bottom' => '5']);

        return $pdf->download(date('Ymd') . '-lames.pdf');
    }
}
