@extends('layouts.pointage')

@section('content')
    <h1>{{ $adherent->prenom }} {{ $adherent->nom }}</h1>

    <p><strong>Numéro de licence:</strong> {{ $adherent->numero_licence }}</p>
    <p><strong>Niveau:</strong> {{ $adherent->niveau }}</p>

    @if($adherent->adhesions->isNotEmpty())
        @foreach($adherent->adhesions as $adhesion)
            <p><strong>Groupe:</strong> {{ $adhesion->groupe->nom }} ({{ $adhesion->etat }})</p>
        @endforeach
        <p><strong>Passages de lame:</strong></p>
        <ul>
            @forelse ($adherent->passages as $passage)
                <li>{{ $passage->date }} - {{ $passage->niveau }} - {{ $passage->etat }}</li>
            @empty
                <li>Aucun passage de lame enregistré.</li>
            @endforelse
        </ul>
    @endif
@endsection
