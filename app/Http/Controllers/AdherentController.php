<?php

namespace App\Http\Controllers;
use App\Models\Adherent;

use Illuminate\Http\Request;

class AdherentController extends Controller
{
    public function show($id) {
        $adherent = Adherent::find($id);
        return view('adherent.show', ['adherent' => $adherent]);
    }
}
