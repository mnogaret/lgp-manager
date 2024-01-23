@php
    $niveau_colors = [
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

    $etapes = [
        'Patin Rouge' => [
            'Je traverse la largeur de la piste',
            'Je fais la trottinette',
            'Je marche autour d’un cercle ou autre objet',
            'Je passe par-dessus un objet',
            'Je recule avec l’aide d’un camarade ou en me repoussant de la barrière'
        ],
        'Lame 1' => [
            'Aller: Marcher en avant puis glisser debout sur 2 pieds ; Retour: Marcher en avant puis glisser sur 2 pieds en position accroupie',
            'Enchaînement de citrons avant',
            'Chasse neige à l’arrêt',
            'Rotation sur 2 pieds en piétinant',
            'Petit saut sur place'
        ],
        'Lame 2' => [
            'Aller: Déplacement en avant en slalom, mains sur les hanches ou la taille, avec arrêt chasse neige ; Retour: Déplacement en arrière en levant les pieds',
            'Enchaînement de citrons en arrière',
            'Enchaînement de Fentes latérales en dehors avant (4 au moins) sur un cercle dans un sens puis dans l’autre',
            'Retournement d’avant en arrière',
            'Une cigogne en avant sur chaque pied ≥ 3 secondes avec maintient des bras',
            'Petit saut en avant ou en arrière avec appel et réception sur 2 pieds'
        ],
        'Lame 3' => [
            'Aller: Déplacement en avant avec des poussées de carre ; Retour: Déplacement en arrière avec des poussées de carre, arrêt dérapage',
            'Enchaînement de Croisés ou Courus en avant sur un cercle dans un sens puis dans l’autre (au moins 4 croisés par cercle)',
            'Enchaînement de fentes latérales paBnées en dehors avant et en lobe avec port(s) de bras',
            'Enchaînement de Fentes latérales arrière (4 au moins) sur un cercle dans un sens puis dans l’autre, avec port (s) de bras ou mainBen simple des bras',
            'Une cigogne en arrière sur chaque pied ≥ 3 secondes avec maintien des bras',
            'Fente en avant ou glissade sur un genou avec port de bras puis révérence (Fille) ou Salut (Garçon)'
        ],
        'Lame 4' => [
            'Enchaînement de chassés en avant sur un cercle, terminé par un dehors avant tenu au moins 3 secondes ; avec port de bras ou maintien des bras, dans un sens puis dans l’autre',
            'Retournement glissé sur 2 pieds d’avant en arrière en courbe',
            'Retournement glissé sur 2 pieds d’arrière en avant',
            'Saut d’un ½ tour. Avec appel et réception sur 2 pieds en glissant',
            'Pirouette solo de 2 rotations minimum sur 2 pieds',
            'Enchaînement de croisés en arrière sur un cercle dans un sens puis dans l’autre'
        ],
        'Lame 5' => [
            'En musique: Enchaînement d’un chassé suivi d’un croisé (ou couru) en avant sur un cercle dans un sens puis dans l’autre terminé par une sortie avec port de bras et de tête.',
            'En musique: Enchaînement de chassés en arrière paBnés sur un cercle dans un sens puis dans l’autre, terminé par un long dehors arrière avec port de bras',
            'Arabesque en avant et en carre maintenue au moins 3 secondes avec port de bras',
            '1 Trois en dehors sur 1 pied, départ à l’arrêt et carre de sortie maintenue; dans un sens puis dans l’autre',
            '2 pas de géant terminés par un freinage parallèle ou freinage sur une carre (Arrêt complet)',
            '1 saut de valse'
        ],
        'Lame 6' => [
            'En musique: Longueur 1 : Enchaînement de 4 lobes composés de Chassé/ Balancé en dehors avant, effectués alternativement sur un pied puis sur l’autre',
            'En musique: Longueur 2 : Enchaînement de 4 lobes composés de chassés ou courus / dehors en arrière réalisés alternativement sur un pied puis sur l’autre. Le tout finit par un freinage en dedans arrière ou une sortie avec port de bras.',
            'AItude en carre (Sur un pied, aigle, fente INA...)',
            'PiroueJe de 2 tours minimum sur un pied',
            'Un ½ Flip',
        ],
        'Lame 7' => [
            'En musique: Enchaînement de croisés ou courus suivi d’un dedans en avant patinés en lobes alternés ( 2 lobes minimum terminés par une sortie avec port de bras)',
            'Attitude en carre sur chaque pied (A l’exception de l’arabesque) avec jambe libre maintenue > de l’horizontale 3 secondes minimum',
            'Enchaînement de cross rolls en avant',
            'Une cafetière en avant ou en arrière',
            'PiroueJe solo de 3 rotations sur 1 pied avec sortie sur 1 pied',
            'Saut de trois sur chaque pied'
        ],
        'Lame 8' => [
            'Enchaînement de 2 trois minimum en dehors avant terminé par une sortie avec port de bras',
            'Enchaînement de 4 lobes en dehors avant puis 4 lobes en dedans avant patinés sur un axe matérialisé. (Déplacement Aller/Retour)',
            'Exécution d’un Mohawk ouvert en dedans avec sortie glissée sur 1 pied puis sur l’autre',
            'Enchaînement de 2 lobes minimum composés d’un croisé en arrière suivi d’un dehors arrière avec port de bras',
            'Pirouette debout démarrée en dehors arrière avec 2 rotations minimum sur 1 pied',
            'Saut d’un tour (Au choix)',
        ]
    ];



@endphp

<table class="adherents">
    <thead>
        <tr>
            <th colspan="2">Prénom NOM</th>
            @foreach ($etapes[$impression['nom']] as $etape)
                <th>{{ $etape }}</th>
            @endforeach
            <th>Résultat oui ou non</th>
        </tr>
    </thead>
    <tbody>
        @foreach (range(0, 23) as $i)
            @php
                $personne = null;
                $adhesion = null;
                if (isset($adherents[$i]))
                {
                    $personne = $adherents[$i];
                    $adhesion = $personne->adhesions[0];
                }
            @endphp
            <tr>
                <td class="centered">
                    {{ $i + 1 }}
                </td>
                <td>
                    {{ $personne?->nom }} {{ $personne?->prenom }}
                </td>
                @foreach ($etapes[$impression['nom']] as $etape)
                    <td></td>
                @endforeach
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
