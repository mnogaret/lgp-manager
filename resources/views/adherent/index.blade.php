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

    <table id="adherentsTable" class="display compact">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Groupe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adherents as $personne)
                <tr onclick="window.location='adherent/{{ $personne->id }}'" class="cursor-pointer">
                    <td>{{ $personne->nom }}</td>
                    <td>{{ $personne->prenom }}</td>
                    <td>{{ $personne->email1 }}</td>
                    <td>
                        @foreach ($personne->adhesions as $adhesion)
                            <p>{{ $adhesion->groupe->nom }}</p>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#adherentsTable', {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                },
            });
        });
    </script>
@endsection
