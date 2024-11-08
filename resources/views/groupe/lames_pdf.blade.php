@php
    use Carbon\Carbon;
    $niveau_suivant = [
        'Patin Bleu' => 'Patin Rouge',
        'Patin Rouge' => 'Lame 1',
        'Lame 1' => 'Lame 2',
        'Lame 2' => 'Lame 3',
        'Lame 3' => 'Lame 4',
        'Lame 4' => 'Lame 5',
        'Lame 5' => 'Lame 6',
        'Lame 6' => 'Lame 7',
        'Lame 7' => 'Lame 8',
    ];
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
    @php
    $newpage = false;
    @endphp
    @foreach ($impressions as $impression)
        @php
        if (isset($impression['groupes'])) {
            $sous_groupes = count($impression['groupes']) > 1;
            $lettres = "";
            if (isset($impression['from']) && isset($impression['to'])) {
                $lettres = " - de " . $impression['from'] . " à " . chr(ord($impression['to'])-1);
            } else if (isset($impression['from'])) {
                $lettres = " - à partir de " . $impression['from'];
            } else if (isset($impression['to'])) {
                $lettres .= " - jusqu'à " . chr(ord($impression['to'])-1);
            }
            $titre = "Groupe " . $impression['groupe'] . " - Niveau " . $impression['niveau'] . (isset($impression['niveau_null']) ? " (ou sans niveau)" : "") .$lettres;
        } else {
            $titre = "Niveau " . $impression['niveau'];
        }
        $titre .= " => " . $niveau_suivant[$impression['niveau']];
        @endphp
        <div class="entete {{ $newpage ? 'newpage' : 'samepage' }}">
            <h1>{{ $titre }}</h1>
            <p>
                @if (isset($impression['creneaux']))
                    Le
                    @foreach ($impression['creneaux'] as $creneau)
                        <span>{{ strtolower($creneau->jour) }} de {{ Carbon::parse($creneau->heure_debut)->format('H\hi') }}
                            à
                            {{ Carbon::parse($creneau->heure_fin)->format('H\hi') }}</span>{{ $loop->remaining > 1 ? ', le' : (!$loop->last ? ' et le' : '') }}
                    @endforeach
                @endif
            </p>
        </div>

        <x-pdf-lames-adherents-table :impression="$impression" :groupes="$sous_groupes" />
        @php
            $newpage = true;
        @endphp
    @endforeach
</body>

</html>
