<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Models\Personne;
use Illuminate\Support\Facades\Auth;

class PersonneController extends Controller
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
        //
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
        $personne = Personne::findOrFail($id);

        // Passer la personne à la vue et l'afficher
        return view('personne.show', ['personne' => $personne]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Récupérer la personne depuis la base de données
        $personne = Personne::findOrFail($id);

        // Afficher la vue d'édition avec les données de la personne
        return view('personne.edit', compact('personne'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Récupérer la personne depuis la base de données
        $personne = Personne::findOrFail($id);

        $new_values = $request->all();
        unset($new_values['_token']);
        unset($new_values['_method']);
        $diff = array_diff_assoc($new_values, $personne->getOriginal());
        foreach ($diff as $key => $value)
        {
            $diff[$key] = $personne[$key] . " => " . $value;
        }
        // Valider les données et mettre à jour la personne
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email1' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255|different:email1',
            'telephone1' => 'nullable|string|max:20',
            'telephone2' => 'nullable|string|max:20|different:telephone1',
            'adresse_postale' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F',
            'nationalite' => 'nullable|string|max:255',
            'ville_naissance' => 'nullable|string|max:255',
            'date_certificat_medical' => 'nullable|date',
            'nom_assurance' => 'nullable|string|max:255',
            'numero_assurance' => 'nullable|string|max:255',
            'droit_image' => 'nullable|in:O,N',
            'numero_licence' => 'nullable|string|max:255',
            'niveau' => 'nullable|in:Lame 1,Lame 2,Lame 3,Lame 4,Lame 5,Lame 6,Lame 7,Lame 8,Lame 1/2,Lame 3/4,Lame 5/6,Lame 7/8'
        ]);
        $personne->update($validatedData);

        Commentaire::create([
            'user_id' => Auth::user()->id,
            'type' => 'Edit',
            'foyer_id' => $personne->foyer_id,
            'personne_id' => $personne->id,
            'commentaire' => json_encode($diff, JSON_PRETTY_PRINT),
        ]);

        // Rediriger après la mise à jour
        return redirect()->route('adherent.show', $personne->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
