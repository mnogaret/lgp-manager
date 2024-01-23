@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Groupes</title>
    <x-pdf-lames-adherents-style />
    <style>
        .newpage {
            page-break-before: always;
        }
        .samepage {
            margin-top: 5mm;
        }
    </style>
</head>

<body>
    @foreach ($impressions as $impression)
        @php
            $sous_groupes = count($impression['groupes']) > 1;
            $newpage = $impression['nom'] !== 'Patin Rouge';
        @endphp
        <div class="entete {{ $newpage ? 'newpage' : 'samepage' }}">
            <h1>{{ $impression['nom'] }}</h1>
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

        <x-pdf-lames-adherents-table :impression="$impression" :adherents="$impression['adherents']" :niveaux="isset($impression['niveaux']) ? $impression['niveaux'] : false" :groupes="$sous_groupes" />
    @endforeach
</body>

</html>
