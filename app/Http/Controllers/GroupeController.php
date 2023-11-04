<?php

namespace App\Http\Controllers;

use App\Models\Adhesion;
use App\Models\Groupe;
use App\Models\Personne;

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
}
