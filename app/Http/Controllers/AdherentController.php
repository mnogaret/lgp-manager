<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use App\Tools\AdherentDriveScanner;
use App\Tools\AdherentImporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdherentController extends Controller
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

        // Récupérer les adhérents qui ont des adhésions dans la saison active
        $adherents = Personne::whereHas('adhesions.groupe', function ($query) use ($saisonId) {
            $query->where('saison_id', $saisonId);
        })
        ->with(['adhesions' => function($query) use ($saisonId) {
            // Filtrer les adhésions pour ne récupérer que celles de la saison active
            $query->whereHas('groupe', function($query) use ($saisonId) {
                $query->where('saison_id', $saisonId);
            });
        }])
        ->get();

        return view('adherent.index', ['adherents' => $adherents]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer la personne depuis la base de données
        $adherent = Personne::with('adhesions.groupe')->with('documents')->with('passages')->with('foyer.membres')->with('foyer.reglements')->findOrFail($id);

        // Passer la personne à la vue et l'afficher
        return view('adherent.show', ['adherent' => $adherent]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $path = $request->file('csv_file')->getRealPath();
        $importer = new AdherentImporter();
        try {
            $importer->from_csv(file_get_contents($path));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('success', print_r($importer->traces, true));
    }

    public function scanDrive(Request $request)
    {
        $scanner = new AdherentDriveScanner();
        $scanner->from_drive();
        return redirect()->back()->with('success', print_r($scanner->traces, true));
    }

    public function generateFacture(string $id)
    {
        $adherent = Personne::with('adhesions.groupe')->with('documents')->with('foyer.membres')->with('foyer.reglements')->findOrFail($id);
        return Pdf::loadView('pdf/facture', ['adherent' => $adherent])->download('facture-' . $adherent->nom . '-' . $adherent->prenom . '.pdf');
    }
}
