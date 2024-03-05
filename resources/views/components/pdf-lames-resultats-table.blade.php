@php
    use Carbon\Carbon;
    Carbon::setLocale('fr');
@endphp

@php
    if (isset($impression['groupes'])) {
        $sous_groupes = count($impression['groupes']) > 1;
        $titre = "Groupe " . $impression['groupe'];
    }
@endphp

<table class="adherents">
    <thead>
        <tr class="titre">
            <th colspan="5">
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
            </th>
        </tr>
        <tr>
            <th class="centered">Nom</th>
            <th class="centered">Prénom</th>
            <th class="centered">Date de passage</th>
            <th class="centered">Niveau visé</th>
            <th class="centered">Obtention</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($impression['resultats'] as $resultat)
            @php
                $personne = $resultat['adherent'];
                $passage = $resultat['passage'];
                $adhesion = $personne->adhesions[0];
            @endphp
            <tr>
                <td>
                    {{ $personne->nom }}
                </td>
                <td>
                    {{ $personne->prenom }}
                </td>
                <td class="centered">{{ $passage->date ? Carbon::parse($passage->date)->translatedFormat('l d F') : "" }}</td>
                <td class="centered">{{ $passage->niveau }}</td>
                <td class="centered {{ $passage->etat == 'Passé' ? 'background-green' : ($passage->etat == 'Échoué' ? 'background-red' : '') }}">
                    @if ($passage->etat == 'Passé')
                        <span>Oui</span>
                    @elseif ($passage->etat == 'Échoué')
                        <span>Non</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
