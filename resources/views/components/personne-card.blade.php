@if (isset($linkable) && $linkable && count($personne->adhesions) > 0)
    <a href="/adherent/{{ $personne->id }}">
@else
    <a>
@endif
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $personne->prenom }}
        {{ $personne->nom }}
        @if ($personne->sexe === 'M')
            <i class="fas fa-mars text-blue-500"></i>
        @endif
        @if ($personne->sexe === 'F')
            <i class="fas fa-venus text-pink-400"></i>
        @endif
        @if ($personne->date_naissance && $personne->getAge() <= 18)
            {{ $personne->getAge() }} ans
        @endif
    </h5>
    <p>{{ $personne->email1 }}</p>
    <p>{{ $personne->email2 }}</p>
    <p>{{ $personne->telephone1 }}</p>
    <p>{{ $personne->telephone2 }}</p>
    <p>{{ $personne->adresse_postale ? $personne->adresse_postale . ' - ' . $personne->code_postal . ' - ' . $personne->ville : '' }}
    </p>
    <p>{{ $personne->date_naissance ? 'Né le ' . $personne->date_naissance . ' à ' . $personne->ville_naissance : '' }}
    </p>
    <p>{{ $personne->nationalite ? 'Nationalité : ' . $personne->nationalite : '' }}</p>
    <p>{{ $personne->nom_assurance ? $personne->nom_assurance . ' | ' . $personne->numero_assurance : '' }}</p>
    <p>{{ $personne->droit_image }}</p>
    <p>{{ $personne->numero_licence }}</p>
</div>
</a>
