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
            $newpage = $impression['groupe'] !== 'Baby';
            $lettres = "";
            if (isset($impression['from']) && isset($impression['to'])) {
                $lettres = " - de " . $impression['from'] . " à " . chr(ord($impression['to'])-1);
            } else if (isset($impression['from'])) {
                $lettres = " - à partir de " . $impression['from'];
            } else if (isset($impression['to'])) {
                $lettres .= " - jusqu'à " . chr(ord($impression['to'])-1);
            }
        @endphp
        <div class="entete {{ $newpage ? 'newpage' : 'samepage' }}">
            <h1>Groupe {{ $impression['groupe'] }} - Niveau {{ $impression['niveau'] }}{{ isset($impression['niveau_null']) ? " (ou sans niveau)" : "" }}{{ $lettres }}</h1>
            <p>
                Le
                @foreach ($impression['creneaux'] as $creneau)
                    <span>{{ strtolower($creneau->jour) }} de {{ Carbon::parse($creneau->heure_debut)->format('H\hi') }}
                        à
                        {{ Carbon::parse($creneau->heure_fin)->format('H\hi') }}</span>{{ $loop->remaining > 1 ? ', le' : (!$loop->last ? ' et le' : '') }}
                @endforeach
            </p>
        </div>

        <x-pdf-lames-adherents-table :impression="$impression" :groupes="$sous_groupes" />
    @endforeach
</body>

</html>
