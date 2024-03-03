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
            {{ $personne->getAge() }}&nbsp;ans
        @endif
    </h5>
    <p>
        <a href="/personne/{{ $personne->id }}/edit"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                <path d="M12.687 14.408a3.01 3.01 0 0 1-1.533.821l-3.566.713a3 3 0 0 1-3.53-3.53l.713-3.566a3.01 3.01 0 0 1 .821-1.533L10.905 2H2.167A2.169 2.169 0 0 0 0 4.167v11.666A2.169 2.169 0 0 0 2.167 18h11.666A2.169 2.169 0 0 0 16 15.833V11.1l-3.313 3.308Zm5.53-9.065.546-.546a2.518 2.518 0 0 0 0-3.56 2.576 2.576 0 0 0-3.559 0l-.547.547 3.56 3.56Z"/>
                <path d="M13.243 3.2 7.359 9.081a.5.5 0 0 0-.136.256L6.51 12.9a.5.5 0 0 0 .59.59l3.566-.713a.5.5 0 0 0 .255-.136L16.8 6.757 13.243 3.2Z"/>
            </svg>
            Éditer
        </a>
    </p>
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
    <p>{{ $personne->date_certificat_medical ? 'Certificat médical : ' . $personne->date_certificat_medical : '' }}</p>
    <p>
        @if ($personne->droit_image)
            Droit à l’image : <x-badge-oui-non :value="$personne->droit_image" />
        @endif
    </p>
    <p>{{ $personne->numero_licence ? 'Licence : ' . $personne->numero_licence : '' }}</p>
    <p>
        @if ($personne->niveau)
            Niveau : <x-niveau :niveau="$personne->niveau" />
        @endif
    </p>

    @if (count($personne->adhesions) > 0)
        <h5 class="my-4 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Adhésions</h5>
        <ul>
            @foreach ($personne->adhesions as $adhesion)
                <x-adhesion-li :adhesion="$adhesion" />
            @endforeach
        </ul>
        <a href="/facture/{{ $personne->id }}"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M6 1v4a1 1 0 0 1-1 1H1m14-4v16a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V5.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 1h8.239A.97.97 0 0 1 15 2Z" />
            </svg>
            Facture (brouillon, non définitif)
        </a>
    @endif

    @if (count($personne->passages) > 0)
        <h5 class="my-4 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Passages le lame</h5>
        <ul>
            @foreach ($personne->passages as $passage)
                <x-passage-li :passage="$passage" />
            @endforeach
        </ul>
    @endif

</div>
</a>
