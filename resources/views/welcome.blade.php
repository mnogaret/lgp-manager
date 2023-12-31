@extends('layouts.app')

@section('content')
    @php
        $versions = [
            [
                'title' => 'TODO',
                'features' => [
                    'Gestion des pointages',
                    'Affichage des pointages',
                    'Édition des adhérents',
                    'Suivi de la facturation',
                ],
            ],
            [
                'title' => '31/12/2023',
                'features' => ['Retrait de l’enum sur la nationalité', 'Suppression des doublons de commentaire'],
            ],
            [
                'title' => '07/11/2023',
                'features' => ['Page des groupes', 'Génération de listes en PDF'],
            ],
            [
                'title' => '03/11/2023',
                'features' => ['Notion de foyer', 'Nouveau layout - Découverte des composants blade', 'Page des adhérents', 'Notion de pièces de dossier (documents)', 'Notion de foyer', 'Notion de commentaires', 'Notions de facturation'],
            ],
            [
                'title' => '25/10/2023',
                'features' => ['Adoption de Frostbite', 'Notion de saison', 'Notion de groupes', 'Notion de créneaux', 'Imports d’adhérents'],
            ],
            [
                'title' => '20/10/2023',
                'features' => ['Menus', 'Déconnexion'],
            ],
            [
                'title' => '16/10/2023',
                'features' => ['Google Auth', 'Tailwind CSS', 'Notion de personnes', 'Notion de foyers'],
            ],
            [
                'title' => '08/10/2023',
                'features' => ['Initialisation du projet'],
            ],
        ];
    @endphp
    <x-accordion id="versions-accordion" dataAccordion="collapse">
        @foreach ($versions as $version)
            <x-accordion-heading id="versions-accordion"
                index="{{ $loop->index }}">{{ $version['title'] }}</x-accordion-heading>
            <x-accordion-body id="versions-accordion" index="{{ $loop->index }}">
                <ul>
                    @foreach ($version['features'] as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </x-accordion-body>
        @endforeach
    </x-accordion>
@endsection
