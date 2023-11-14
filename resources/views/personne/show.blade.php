@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la personne</h1>

    <x-input id="nom" label="Nom" value="{{ $personne->nom }}" />
    <x-input id="prenom" label="Prénom" value="{{ $personne->prenom }}" />
    <x-input id="sexe" label="Sexe" value="{{ $personne->sexe }}" />
    <x-input id="nationalite" label="Nationalité" value="{{ $personne->nationalite }}" />
    <x-input id="date_naissance" label="Date de naissance" value="{{ $personne->date_naissance }}" />
    <x-input id="ville_naissance" label="Ville de naissance" value="{{ $personne->ville_naissance }}" />
    <x-input id="email1" label="Email 1" value="{{ $personne->email1 }}" />
    <x-input id="email2" label="Email 2" value="{{ $personne->email2 }}" />
    <x-input id="telephone1" label="Téléphone 1" value="{{ $personne->telephone1 }}" />
    <x-input id="telephone2" label="Téléphone 2" value="{{ $personne->telephone2 }}" />
    <x-input id="adresse_postale" label="Adresse postale" value="{{ $personne->adresse_postale }}" />
    <x-input id="code_postal" label="Code postal" value="{{ $personne->code_postal }}" />
    <x-input id="ville" label="Ville" value="{{ $personne->ville }}" />
    <x-input id="numero_licence" label="Numéro de licence" value="{{ $personne->numero_licence }}" />
</div>
@endsection