@php
    $niveau_colors = [
        'Patin Bleu' => 'white',
        'Patin Rouge' => 'white',
        'Lame 1' => 'white',
        'Lame 2' => 'yellow',
        'Lame 3' => 'orange',
        'Lame 4' => 'green',
        'Lame 5' => 'blue',
        'Lame 6' => 'purple',
        'Lame 7' => 'brown',
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

    $short_groupe = [
        '2023-baby-mar' => 'Mardi',
        '2023-baby-ven' => 'Vendredi',
        '2023-adulte-deb-mar' => 'Débutant',
        '2023-adulte-deb-ven' => 'Débutant',
        '2023-adulte-deb-sam' => 'Débutant',
        '2023-adulte-int-mar' => 'Intermédiaire',
        '2023-adulte-int-ven' => 'Intermédiaire',
        '2023-adulte-int-sam' => 'Intermédiaire',
        '2023-adulte-dan-mar' => 'Danseur',
        '2023-adulte-dan-ven' => 'Danseur',
        '2023-adulte-dan-sam' => 'Danseur',
        '2023-adulte-sau-mar' => 'Sauteur',
        '2023-adulte-sau-ven' => 'Sauteur',
        '2023-adulte-sau-sam' => 'Sauteur',
    ];

    $groupe_color = [
        'Mardi' => 'green',
        'Vendredi' => 'blue',
        'Débutant' => 'green',
        'Intermédiaire' => 'yellow',
        'Danseur' => 'blue',
        'Sauteur' => 'red',
    ];
@endphp

<table class="adherents">
    <thead>
        <tr>
            <th>Id</th>
            <th>Présent</th>
            <th>Nom</th>
            <th>Prénom</th>
            @if ($groupes)
                <th>Groupe</th>
            @endif
            @if ($niveaux)
                <th>Âge</th>
                <th>Niveau</th>
            @endif
            <th>Inscription</th>
            <th>Téléphone</th>
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
                    class="{{ array_key_exists($adhesion->etat, $warning_colors) ? 'background-' . $warning_colors[$adhesion->etat] : '' }}">
                    {{ $personne->nom }}
                </td>
                <td
                    class="{{ array_key_exists($adhesion->etat, $warning_colors) ? 'background-' . $warning_colors[$adhesion->etat] : '' }}">
                    {{ $personne->prenom }}
                </td>
                @if ($groupes)
                    @php
                        $groupe = isset($short_groupe[$adhesion->groupe->code]) ? $short_groupe[$adhesion->groupe->code] : $adhesion->groupe->nom;
                    @endphp
                    <td
                        class="{{ array_key_exists($groupe, $groupe_color) ? 'background-' . $groupe_color[$groupe] : '' }}">
                        {{ $groupe }}
                    </td>
                @endif
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
                <td class="centered">{{ $personne->getTelephone() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
