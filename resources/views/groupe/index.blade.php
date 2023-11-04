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

    <table id="groupesTable" class="display compact">
        <thead>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th>Réglés</th>
                <th>Inscrits</th>
                <th>Liste d’attente</th>
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
                    <td>{{ count($groupe->getRegle()) }}</td>
                    <td>{{ count($groupe->getInscrits()) }}</td>
                    <td>{{ count($groupe->getListeAttente()) }}</td>
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
