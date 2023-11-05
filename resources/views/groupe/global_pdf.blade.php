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
            $sous_groupes = count($impression['groupes']) > 1;
        @endphp
        <div class="entete {{ $loop->first ? '' : 'newpage' }}">
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

        <x-pdf-adherents-table :adherents="$impression['adherents']" :niveaux="isset($impression['niveaux']) ? $impression['niveaux'] : false" :groupes="$sous_groupes" />
    @endforeach
</body>

</html>
