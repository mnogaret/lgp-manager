{{-- Exemple basique d'affichage des informations --}}

<h1>Séance: {{ $seance->code }}</h1>
<p>Groupe: {{ $groupe->nom }}</p>

<h2>Présences</h2>
<ul>
    @foreach ($presences as $presence)
        <li>{{ $presence->personne->nom }} - {{ $presence->statut }}</li>
    @endforeach
</ul>