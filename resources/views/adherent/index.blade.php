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
                <tr>
                    <td>
                        <a href="{{ route('adherent.show', $personne->id) }}" class="block hover:underline">
                            {{ $personne->nom }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('adherent.show', $personne->id) }}" class="block hover:underline">
                            {{ $personne->prenom }}</a>
                    </td>
                    <td>
                        <a href="{{ route('adherent.show', $personne->id) }}" class="block hover:underline">
                            {{ $personne->email1 }}</a>
                    </td>
                    <td>
                        <ul>
                            @foreach ($personne->adhesions as $adhesion)
                                <x-adhesion-li :adhesion="$adhesion" />
                            @endforeach
                        </ul>
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
