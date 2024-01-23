@extends('layouts.pointage')

@php
    $niveaux = false;
    foreach ($personnes as $personne) {
        if (!empty($personne->niveau)) {
            $niveaux = true;
            break;
        }
    }
    $niv = [
        'niveau-sans' => 'Sans niveau',
        'niveau-0' => 'Patin Rouge',
        'niveau-1' => 'Lame 1',
        'niveau-2' => 'Lame 2',
        'niveau-3' => 'Lame 3',
        'niveau-4' => 'Lame 4',
        'niveau-5' => 'Lame 5',
        'niveau-6' => 'Lame 6',
        'niveau-7' => 'Lame 7',
        'niveau-8' => 'Lame 8',
    ];
@endphp

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

    <h1>Séance du {{ $seance->date }} de {{ $seance->heure_debut }} à {{ $seance->heure_fin }}</h1>
    <p>Groupe: {{ $groupe->nom }}</p>

    @if ($niveaux)
        <p>Afficher les niveaux :
            @foreach ($niv as $key => $value)
                <x-niveau id="{{ $key }}-1" niveau="{{ $value }}" :hidden="true" />
                <x-niveau id="{{ $key }}-0" niveau="{{ $value }}" :plain="false" />
            @endforeach
        </p>
    @endif
    <p>Afficher les pointés présent</p>
    <p>Afficher les pointés absent</p>

    <table id="pointageTable" class="display compact">
        <thead>
            <tr>
                <th>Prenom</th>
                <th>Nom</th>
                @if ($niveaux)
                    <th>Niveau</th>
                @endif
                <th>Présence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personnes as $personne)
                <tr class="hover:text-blue-100">
                    <td>
                        {{ $personne->prenom }}
                    </td>
                    <td>
                        {{ $personne->nom }}
                    </td>
                    @if ($niveaux)
                        <td>
                            <x-niveau :niveau="$personne->niveau" />
                        </td>
                    @endif
                    <td>
                        <x-icon-button id="present-0-{{ $personne->id }}" icon="check" color="green" :plain="false"
                            :hidden="true" />
                        <x-icon-button id="present-1-{{ $personne->id }}" icon="check" color="green" :plain="true"
                            :hidden="true"
                            onClick="updatePresence('{{ $seance->code }}', {{ $personne->id }}, 'Présent')" />
                        <x-icon-button id="present-s-{{ $personne->id }}" icon="spinner" color="green" :plain="false"
                            :hidden="true" />
                        <x-icon-button id="absent-0-{{ $personne->id }}" icon="close" color="red" :plain="false"
                            :hidden="true"
                            onClick="updatePresence('{{ $seance->code }}', {{ $personne->id }}, 'Non pointé')" />
                        <x-icon-button id="absent-1-{{ $personne->id }}" icon="close" color="red" :plain="true"
                            :hidden="true"
                            onClick="updatePresence('{{ $seance->code }}', {{ $personne->id }}, 'Absent')" />
                        <x-icon-button id="absent-s-{{ $personne->id }}" icon="spinner" color="red" :plain="false"
                            :hidden="true" />
                    </td>
                </tr>
            @endforeach
        <tbody>
    </table>
    <script>
        var presences = @json($presences);
        var seanceCode = @json($seance->code);
        var niveaux = [];
        var niv = @json($niv);

        @if ($niveaux)
            DataTable.ext.search.push(function(settings, data, dataIndex) {
                let niveau = data[2] || 'Sans niveau';
                return niveaux.length === 0 || niveaux.indexOf(niveau) >= 0;
            });
        @endif

        $(document).ready(function() {

            let table = new DataTable('#pointageTable', {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                },
                paging: false,
            });
            displayPresences();

            @if ($niveaux)
                const t = table;
                for (var key in niv) {
                    const k = key;
                    document.getElementById(`${k}-0`).style.cursor = 'pointer';
                    document.getElementById(`${k}-0`).addEventListener('click', () => {
                        document.getElementById(`${k}-0`).classList.add('hidden');
                        document.getElementById(`${k}-1`).classList.remove('hidden');
                        niveaux.push(niv[k]);
                        t.draw();
                    });
                    document.getElementById(`${k}-1`).style.cursor = 'pointer';
                    document.getElementById(`${k}-1`).addEventListener('click', () => {
                        document.getElementById(`${k}-1`).classList.add('hidden');
                        document.getElementById(`${k}-0`).classList.remove('hidden');
                        niveaux = niveaux.filter((v) => v !== niv[k]);
                        t.draw();
                    });
                }
            @endif

            for (var id in presences) {
                const personneId = id;
                document.getElementById(`present-0-${personneId}`).addEventListener('click', () => {
                    updatePresence(seanceCode, personneId, 'present', 'Présent');
                });
                document.getElementById(`present-1-${personneId}`).addEventListener('click', () => {
                    updatePresence(seanceCode, personneId, 'present', 'Non pointé');
                });
                document.getElementById(`absent-0-${personneId}`).addEventListener('click', () => {
                    updatePresence(seanceCode, personneId, 'absent', 'Absent');
                });
                document.getElementById(`absent-1-${personneId}`).addEventListener('click', () => {
                    updatePresence(seanceCode, personneId, 'absent', 'Non pointé');
                });
            }
        });

        function displayPresence(id) {

            let present0 = document.getElementById(`present-0-${id}`);
            let present1 = document.getElementById(`present-1-${id}`);
            let presentS = document.getElementById(`present-s-${id}`);
            let absent0 = document.getElementById(`absent-0-${id}`);
            let absent1 = document.getElementById(`absent-1-${id}`);
            let absentS = document.getElementById(`absent-s-${id}`);

            if (presentS === null) {
                return;
            }

            if (presentS.classList.contains("hidden")) {
                if (presences[id] === 'Présent') {
                    present0.classList.add("hidden");
                    present1.classList.remove("hidden");
                } else {
                    present0.classList.remove("hidden");
                    present1.classList.add("hidden");
                }
            }

            if (absentS.classList.contains("hidden")) {
                if (presences[id] === 'Absent') {
                    absent0.classList.add("hidden");
                    absent1.classList.remove("hidden");
                } else {
                    absent0.classList.remove("hidden");
                    absent1.classList.add("hidden");
                }
            }
        }

        function displayPresences() {
            for (var id in presences) {
                displayPresence(id);
            }
        }

        async function updatePresence(codeSeance, id, button, statut) {
            // Afficher l'indicateur d'activité
            document.getElementById(`${button}-0-${id}`).classList.add("hidden");
            document.getElementById(`${button}-1-${id}`).classList.add("hidden");
            document.getElementById(`${button}-s-${id}`).classList.remove("hidden");

            try {
                const response = await fetch(`/api/pointage/${codeSeance}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        // Ajouter des en-têtes supplémentaires si nécessaire (comme des jetons d'authentification)
                    },
                    body: JSON.stringify({
                        statut: statut
                    })
                });

                if (!response.ok) {
                    throw new Error('Erreur de réseau ou réponse non réussie');
                }

                const data = await response.json();

                presences = data['presences'];
                document.getElementById(`${button}-s-${id}`).classList.add("hidden");
                displayPresences();
            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
            }
        }
    </script>
@endsection
