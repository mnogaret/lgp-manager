@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la personne</h1>

    <x-input label="Nom" value="{{ $personne->nom }}" />
    <x-input label="Prénom" value="{{ $personne->prenom }}" />
    <x-input label="Sexe" value="{{ $personne->sexe }}" />
    <x-input label="Nationalité" value="{{ $personne->nationalite }}" />
    <x-input label="Date de naissance" value="{{ $personne->date_naissance }}" />
    <x-input label="Ville de naissance" value="{{ $personne->ville_naissance }}" />
    <x-input label="Email 1" value="{{ $personne->email1 }}" />
    <x-input label="Email 2" value="{{ $personne->email2 }}" />
    <x-input label="Téléphone 1" value="{{ $personne->telephone1 }}" />
    <x-input label="Téléphone 2" value="{{ $personne->telephone2 }}" />
    <x-input label="Adresse postale" value="{{ $personne->adresse_postale }}" />
    <x-input label="Code postal" value="{{ $personne->code_postal }}" />
    <x-input label="Ville" value="{{ $personne->ville }}" />
    <x-input label="Numéro de licence" value="{{ $personne->numero_licence }}" />
</div>
@endsection