@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer une nouvelle séance pour le groupe {{ $groupe->nom }} le {{ $creneau->jour }} de {{ $creneau->heure_debut }} à {{ $creneau->heure_fin }}</h1>
        <form method="POST" action="{{ route('seance.store', ['groupe' => $groupe->id, 'creneau' => $creneau->id]) }}">
            @csrf
            <x-input type="date" id="date" label="Date" />
            <x-submit>Créer Séance</x-submit>
        </form>
    </div>
@endsection
