<?php

namespace App\Http\Controllers;

use App\Models\Saison;
use Illuminate\Http\Request;

class SaisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function select(Request $request)
    {
        // Récupérer la saison sélectionnée
        $saison = Saison::find($request->input('saison'));

        if ($saison) {
            // Stocker l'ID de la saison dans la session
            $request->session()->put('saison_id', $saison->id);
        }

        return redirect()->back(); // Rediriger vers la page précédente
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
    public function create(Request $request)
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
}
