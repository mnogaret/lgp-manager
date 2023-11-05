@php
    use Carbon\Carbon;

    $niveaux = in_array($groupe->code, ['2023-ados', '2023-lame1', '2023-lame2+', '2023-baby-mar', '2023-baby-ven']);
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Groupe {{ $groupe->nom }}</title>
    <x-pdf-adherents-style />
</head>

<body>
    <div class="entete">
        <h1>Groupe {{ $groupe->nom }}</h1>
        <p>
            Le
            @foreach ($groupe->creneaux as $creneau)
                <span>{{ strtolower($creneau->jour) }} de {{ Carbon::parse($creneau->heure_debut)->format('H\hi') }} à
                    {{ Carbon::parse($creneau->heure_fin)->format('H\hi') }}</span>{{ $loop->remaining > 1 ? ', le' : (!$loop->last ? ' et le' : '') }}
            @endforeach
            – {{ count($adherents) }} membres
        </p>
    </div>

    <x-pdf-adherents-table :adherents="$adherents" :niveaux="$niveaux" :groupes="false" />
</body>

</html>
