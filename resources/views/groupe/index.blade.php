@extends('layouts.app')

@section('content')
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

    <!-- Scripts -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.tailwindcss.com/3.3.5"></script>

    <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Groupes</h5>
        <a href="{{ route('groupes.pdf') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M6 1v4a1 1 0 0 1-1 1H1m14-4v16a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V5.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 1h8.239A.97.97 0 0 1 15 2Z" />
            </svg>
            Télécharger
        </a>
        <a href="{{ route('groupes.lames-pdf') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M6 1v4a1 1 0 0 1-1 1H1m14-4v16a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V5.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 1h8.239A.97.97 0 0 1 15 2Z" />
            </svg>
            Passages de lames
        </a>
        <a href="{{ route('passages.lames-pdf') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 inline-flex items-center focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-3 h-3 mr-2 text-gray-800 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                    d="M6 1v4a1 1 0 0 1-1 1H1m14-4v16a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V5.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 1h8.239A.97.97 0 0 1 15 2Z" />
            </svg>
            Résultats
        </a>
    </div>

    <table id="groupesTable" class="display compact">
        <thead>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th>Jours</th>
                <th>Réglés</th>
                <th>Inscrits</th>
                <th>Liste d’attente</th>
                <th>Séances</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupes as $groupe)
                <tr class="hover:text-blue-100">
                    <td>
                        <a href="{{ route('groupe.show', $groupe->id) }}" class="block hover:underline">
                            {{ $groupe->type }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('groupe.show', $groupe->id) }}" class="block hover:underline">
                            {{ $groupe->nom }}
                        </a>
                    </td>
                    <td>
                        <ul>
                            @foreach ($groupe->creneaux as $creneau)
                                <li>{{ $creneau->jour }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ count($groupe->getRegle()) }}</td>
                    <td>{{ count($groupe->getInscrits()) }}</td>
                    <td>{{ count($groupe->getListeAttente()) }}</td>
                    <td>
                        <ul>
                            @foreach ($groupe->seances as $seance)
                                <li><a href="{{ route('pointage', $seance->code) }}">{{ $seance->date }}</a></li>
                            @endforeach
                            @foreach ($groupe->creneaux as $creneau)
                                <li><a
                                        href="{{ route('seance.create', ['groupe' => $groupe->id, 'creneau' => $creneau->id]) }}">Créer
                                        ({{ $creneau->jour }})
                                    </a></li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#groupesTable', {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                },
            });
        });
    </script>
@endsection
