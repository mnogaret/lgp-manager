@extends('layouts.app')

@section('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/datepicker.min.js"></script>
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('personne.update', $personne->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="nom" label="Nom" :value="$personne->nom" />
                <x-input id="prenom" label="Prénom" :value="$personne->prenom" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-dropdown id="sexe" label="Sexe" :options="['' => 'Non défini', 'M' => 'Homme', 'F' => 'Femme']" value="{{ $personne->sexe }}" />
                <x-input id="nationalite" label="Nationalité" :value="$personne->nationalite" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-date-picker id="date_naissance" label="Date de naissance" :value="$personne->date_naissance" />
                <x-input id="ville_naissance" label="Ville de naissance" :value="$personne->ville_naissance" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="email1" label="Email 1" :value="$personne->email1" />
                <x-input id="email2" label="Email 2" :value="$personne->email2" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="telephone1" label="Téléphone 1" :value="$personne->telephone1" />
                <x-input id="telephone2" label="Téléphone 2" :value="$personne->telephone2" />
            </div>
            <x-input id="adresse_postale" label="Adresse postale" :value="$personne->adresse_postale" />
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="code_postal" label="Code postal" :value="$personne->code_postal" />
                <x-input id="ville" label="Ville" :value="$personne->ville" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="numero_licence" label="Numéro de licence" :value="$personne->numero_licence" />
                <x-dropdown id="niveau" label="Niveau" :options="[
                    '' => 'Non défini', 
                    'Patin Rouge' => 'Patin Rouge', 
                    'Lame 1' => 'Lame 1', 
                    'Lame 2' => 'Lame 2',
                    'Lame 3' => 'Lame 3', 
                    'Lame 4' => 'Lame 4',
                    'Lame 5' => 'Lame 5', 
                    'Lame 6' => 'Lame 6',
                    'Lame 7' => 'Lame 7', 
                    'Lame 8' => 'Lame 8',
                ]" value="{{ $personne->niveau }}" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-input id="nom_assurance" label="Nom de l'assurance" :value="$personne->nom_assurance" />
                <x-input id="numero_assurance" label="Numéro de l'assurance" :value="$personne->numero_assurance" />
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <x-dropdown id="droit_image" label="Droit à l'image" :options="['' => 'Non défini', 'O' => 'Oui', 'N' => 'Non']" :value="$personne->droit_image" />
                <x-date-picker id="date_certificat_medical" label="Date du certificat médical" :value="$personne->date_certificat_medical" />
            </div>
            <x-button type="submit">Mettre à jour</x-button>
        </form>
    </div>
@endsection
