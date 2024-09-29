@extends('layouts.app')

@section('content')
    @php
        $versions = [
            [
                'title' => 'TODO',
                'features' => [
                    'Refacto nécessaire',
                ],
            ],
            [
                'title' => '29/09/2024',
                'features' => ['Gestion des badges (génération csv et zip des photos)'],
            ],
            [
                'title' => '27/09/2024',
                'features' => ['Scan des documents 2024'],
            ],
            [
                'title' => '26/09/2024',
                'features' => ['Nouvelle saison 2024-2025', 'Saison switch', 'Nouveau format d\'import saison 2024-2025'],
            ],
            [
                'title' => '03/03/2024',
                'features' => ['Ajout des passages de lame'],
            ],
            [
                'title' => '23/01/2024',
                'features' => ['Retrait de l’enum sur le niveau', 'Génération des listes pour le passage des lames'],
            ],
            [
                'title' => '01/01/2024',
                'features' => ['Retrait de l’enum sur la nationalité', 'Suppression des doublons de commentaire', 'Édition des adhérents'],
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
