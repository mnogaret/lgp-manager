@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Attestation de paiement</title>
    <style>
        body {
            font-family: 'Inter,ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji';
        }

        .entete>h1 {
            margin: 0;
            padding: 0;
        }

        .entete>span {
            font-weight: bold;
        }

        table.groupe {
            width: 100%;
        }

        table.groupe,
        table.groupe th,
        table.groupe td {
            border: 1px solid black;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="entete">
        <h1>Groupe {{ $groupe->nom }}</h1>
        <p>
            @foreach ($groupe->creneaux as $creneau)
                <span>{{ $creneau->jour }} de {{ Carbon::parse($creneau->heure_debut)->format('H\hi') }} à
                    {{ Carbon::parse($creneau->heure_fin)->format('H\hi') }}</span>{{ $loop->remaining > 1 ? ',' : (!$loop->last ? ' et ' : '') }}
            @endforeach
        </p>
    </div>

    <table class="groupe">
        <thead>
            <tr>
                <th>Id</th>
                <th>Présent</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Âge</th>
                <th>Niveau</th>
                <th>Inscription</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adherents as $personne)
                <tr>
                    <td>
                        {{ $personne->id }}
                    </td>
                    <td></td>
                    <td>
                        {{ $personne->nom }}
                    </td>
                    <td>
                        {{ $personne->prenom }}
                    </td>
                    <td>{{ $personne->getTelephone() }}</td>
                    <td data-order="{{ $personne->getAge() }}">{{ $personne->getAge() }}&nbsp;ans</td>
                    <td>
                        {{ $personne->niveau }}
                    </td>
                    <td class="last">
                        @foreach ($personne->adhesions as $adhesion)
                            {{ $adhesion->etat }}
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
