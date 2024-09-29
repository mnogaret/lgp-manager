@extends('layouts.pointage')

@section('content')
    <h1>Fiche de l'adhérent : {{ $adherent->prenom }} {{ $adherent->nom }}</h1>

    <p><strong>Email:</strong> {{ $adherent->email1 }}</p>
    <p><strong>Date de naissance:</strong> {{ $adherent->date_naissance }}</p>
    <p><strong>Sexe:</strong> {{ $adherent->sexe }}</p>
    <p><strong>Numéro de licence:</strong> {{ $adherent->numero_licence }}</p>

    @if($adherent->adhesions->isNotEmpty())
        <h2>Groupes d'adhésion</h2>
        <ul>
            @foreach($adherent->adhesions as $adhesion)
                <li>{{ $adhesion->groupe->nom }} ({{ $adhesion->etat }})</li>
            @endforeach
        </ul>
    @endif
@endsection
