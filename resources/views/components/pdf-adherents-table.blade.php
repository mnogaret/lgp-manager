@php
    $niveau_colors = [
        'Lame 1' => 'white',
        'Lame 1/2' => 'white',
        'Lame 2' => 'yellow',
        'Lame 3' => 'orange',
        'Lame 3/4' => 'orange',
        'Lame 4' => 'green',
        'Lame 5' => 'blue',
        'Lame 5/6' => 'blue',
        'Lame 6' => 'purple',
        'Lame 7' => 'brown',
        'Lame 7/8' => 'brown',
        'Lame 8' => 'black',
    ];

    $etat_colors = [
        'créé' => 'red',
        'essai' => 'pink',
        'complet' => 'yellow',
        'réglé' => 'blue',
        'validé' => 'green',
    ];

    $warning_colors = [
        'créé' => 'red',
        'complet' => 'red',
        'réglé' => 'yellow',
    ];

@endphp

<table class="adherents">
    <thead>
        <tr>
            <th>Id</th>
            <th>Présent</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            @if ($niveaux)
                <th>Âge</th>
                <th>Niveau</th>
            @endif
            <th>Inscription</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($adherents as $personne)
            @php
                $adhesion = $personne->adhesions[0];
            @endphp
            <tr>
                <td class="centered">
                    {{ $personne->id }}
                </td>
                <td></td>
                <td
                    class="{{ array_key_exists($adhesion->etat, $warning_colors) ? 'warning-' . $warning_colors[$adhesion->etat] : '' }}">
                    {{ $personne->nom }}
                </td>
                <td
                    class="{{ array_key_exists($adhesion->etat, $warning_colors) ? 'warning-' . $warning_colors[$adhesion->etat] : '' }}">
                    {{ $personne->prenom }}
                </td>
                <td class="centered">{{ $personne->getTelephone() }}</td>
                @if ($niveaux)
                    <td class="centered" data-order="{{ $personne->getAge() }}">{{ $personne->getAge() }}&nbsp;ans
                    </td>
                    <td class="centered">
                        @if ($personne->niveau)
                            <span class="niveau color-{{ $niveau_colors[$personne->niveau] }}">
                                {{ $personne->niveau }}
                            </span>
                        @endif
                    </td>
                @endif
                <td class="centered">
                    <span class="color-{{ $etat_colors[$adhesion->etat] }}">
                        {{ $adhesion->etat }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
