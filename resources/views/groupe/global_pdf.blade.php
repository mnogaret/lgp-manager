@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Groupes</title>
    <x-pdf-adherents-style />
    <style>
        .newpage {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @foreach ($impressions as $impression)
        @php
            $niveaux = in_array($impression['nom'], ['Baby', 'Lame 1', 'Lame 2', 'Lame 3', 'Lame 4', 'Lame 5', 'Lame 6', 'Lame 7', 'Lame 8', 'Ados']);
        @endphp
        <div class="entete newpage">
            <h1>Groupe {{ $impression['nom'] }}</h1>
            <p>
                Le
                @foreach ($impression['creneaux'] as $creneau)
                    <span>{{ strtolower($creneau->jour) }} de {{ Carbon::parse($creneau->heure_debut)->format('H\hi') }}
                        à
                        {{ Carbon::parse($creneau->heure_fin)->format('H\hi') }}</span>{{ $loop->remaining > 1 ? ', le' : (!$loop->last ? ' et le' : '') }}
                @endforeach
                – {{ count($impression['adherents']) }} membres
            </p>
        </div>

        <x-pdf-adherents-table :adherents="$impression['adherents']" :niveaux="$niveaux" />
    @endforeach
</body>

</html>
