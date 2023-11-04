@extends('layouts.app')

@section('content')
    <!-- Ce truc définit les couleurs mais pète le light mode -->
    <!-- TODO faire le ménage dans les script et style -->
    <script type="text/javascript" charset="utf8" src="https://cdn.tailwindcss.com/3.3.5"></script>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-personne-card :personne="$adherent" />
        </div>
        <div>
            <x-documents-card :documents="$adherent->documents" />
        </div>
        <div>
            <x-commentaires-card :commentaires="$adherent->foyer->commentaires" />
        </div>
    </div>

    <x-accordion id="facturation-accordion">
        <x-accordion-heading id="facturation-accordion" index="1">Facturation</x-accordion-heading>
        <x-accordion-body id="facturation-accordion" index="1">
            <p>Montant total : {{ $adherent->foyer->montant_total }}</p>
            <p>Montant réglé : {{ $adherent->foyer->montant_regle }}</p>
            <p>-----</p>
            @foreach ($adherent->foyer->reglements as $reglement)
                <p>{{ $reglement->date }} {{ $reglement->type }} {{ $reglement->montant }} {{ $reglement->code ? " (" . $reglement->code . ")" : "" }}</p>
            @endforeach
            <p>Total : {{ $adherent->foyer->montant_total_reglements() }}</p>
            <p>-----</p>
            @php($id_multi_membre = false)
            @php($multi_groupe_types = ['Adulte débutant', 'Adulte intermédiaire', 'Adulte danseur', 'Adulte sauteur'])
            @foreach ($adherent->foyer->membres as $membre)
                @php($is_multi_groupe = false)
                @foreach ($membre->adhesions as $adhesion)
                    <p>{{ $membre->prenom }} {{ $membre->nom }} {{ $adhesion->groupe->nom }} {{ $adhesion->groupe->prix }}
                        @if (in_array($adhesion->groupe->type, $multi_groupe_types))
                            @if ($is_multi_groupe)
                                <p>{{ $membre->prenom }} {{ $membre->nom }} {{ $adhesion->groupe->nom }} Réduction multi-créneau -30</p>
                            @endif
                            @php($is_multi_groupe = true)
                        @endif
                @endforeach
                @if (count($membre->adhesions) > 0)
                    @if ($id_multi_membre)
                        <p>{{ $membre->prenom }} {{ $membre->nom }} Réduction plusieurs membre pas foyer -25</p>
                    @endif
                    @php($id_multi_membre = true)
                @endif
            @endforeach
            <p>Total : {{ $adherent->foyer->montant_total_cotisations() }}</p>
        </x-accordion-body>
    </x-accordion>

    @if (count($adherent->foyer->membres) > 1)
        <x-accordion id="foyer-accordion">
            <x-accordion-heading id="foyer-accordion" index="1">Membres du foyer</x-accordion-heading>
            <x-accordion-body id="foyer-accordion" index="1">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($adherent->foyer->membres as $membre)
                        @if ($adherent->nom !== $membre->nom || $adherent->prenom !== $membre->prenom)
                            <div>
                                <x-personne-card :personne="$membre" :linkable="true" />
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-accordion-body>
        </x-accordion>
    @endif
@endsection
