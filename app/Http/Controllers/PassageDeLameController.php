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

class PassageDeLameController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $path = $request->file('csv_file')->getRealPath();
        $importer = new PassageDeLameImporter();
        try {
            $importer->from_csv(file_get_contents($path));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('success', print_r($importer->traces, true));
    }

    public function lames_pdf()
    {
        $impression_defs = [
            [
                'groupe' => 'Baby',
                'groupes' => ['2023-baby-mar', '2023-baby-ven'],
            ],
            [
                'groupe' => 'Lame 1',
                'groupes' => ['2023-lame1'],
            ],
            [
                'groupe' => 'Lame 2 à 8',
                'groupes' => ['2023-lame2+'],
            ],
            [
                'groupe' => 'Ados',
                'groupes' => ['2023-ados'],
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
                })->whereHas('passages');
                $adherents = $q->orderBy('nom')->orderBy('prenom')->get();

                foreach ($adherents as $adherent) {
                    $passage = PassageDeLame::where('personne_id', $adherent->id)->firstOrFail();
                    $impression['resultats'][] = [
                        'adherent' => $adherent,
                        'passage' => $passage,
                    ];
                }
            }
            if (count($impression['resultats']) > 0) {
                $impressions[] = $impression;
            }
        }

        return Pdf::loadView('passage/lames_pdf', ['impressions' => $impressions])->download(date('Ymd') . '-lames-resultats.pdf');
    }
}
