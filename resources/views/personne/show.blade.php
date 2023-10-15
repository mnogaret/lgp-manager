@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la personne</h1>
    <p><strong>Nom :</strong> {{ $personne->nom }}</p>
    <p><strong>Prénom :</strong> {{ $personne->prenom }}</p>
    <p><strong>Sexe :</strong> {{ $personne->sexe }}</p>
    <p><strong>Nationalité :</strong> {{ $personne->nationalite }}</p>
    <p><strong>Date de naissance :</strong> {{ $personne->date_naissance }}</p>
    <p><strong>Ville de naissance :</strong> {{ $personne->ville_naissance }}</p>
    <p><strong>Email 1 :</strong> {{ $personne->email1 }}</p>
    <p><strong>Email 2 :</strong> {{ $personne->email2 }}</p>
    <p><strong>Téléphone 1 :</strong> {{ $personne->telephone1 }}</p>
    <p><strong>Téléphone 2 :</strong> {{ $personne->telephone2 }}</p>
    <p><strong>Adresse postale :</strong> {{ $personne->adresse_postale }}</p>
    <p><strong>Code postal :</strong> {{ $personne->code_postal }}</p>
    <p><strong>Ville :</strong> {{ $personne->ville }}</p>
    <p><strong>Numéro de licence :</strong> {{ $personne->numero_licence }}</p>

    @if($personne->chef_de_foyer_id === $personne->id)
        <h2>Membres du foyer</h2>
        <ul>
        @foreach($personne->membresDuFoyer as $membre)
            <li>{{ $membre->prenom }} {{ $membre->nom }}</li>
        @endforeach
        </ul>
    @endif
</div>
@endsection