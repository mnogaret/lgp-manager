@php
    $etapes_descr = [
        'Patin Rouge' => [
            'Je traverse la largeur de la piste',
            'Je fais la trottinette',
            'Je marche autour d’un cercle ou autre objet',
            'Je passe par-dessus un objet',
            'Je recule avec l’aide d’un camarade ou en me repoussant de la barrière'
        ],
        'Lame 1' => [
            'Aller: Marcher en avant puis glisser debout sur 2 pieds ; Retour: Marcher en avant puis glisser sur 2 pieds en position accroupie',
            'Enchaîn<sup>t</sup> de citrons avant',
            'Chasse neige à l’arrêt',
            'Rotation sur 2 pieds en piétinant',
            'Petit saut sur place'
        ],
        'Lame 2' => [
            'Aller: Déplac<sup>t</sup> en avant en slalom, mains sur les hanches ou la taille, avec arrêt chasse neige ; Retour: Déplac<sup>t</sup> en arrière en levant les pieds',
            'Enchaîn<sup>t</sup> de citrons en arrière',
            'Enchaîn<sup>t</sup> de Fentes latérales en dehors avant (4 au moins) sur un cercle dans un sens puis dans l’autre',
            'Retourn<sup>t</sup> d’avant en arrière',
            'Une cigogne en avant sur chaque pied ≥ 3 secondes avec maintient des bras',
            'Petit saut en avant ou en arrière avec appel et réception sur 2 pieds'
        ],
        'Lame 3' => [
            'Aller: Déplac<sup>t</sup> en avant avec des poussées de carre ; Retour: Déplac<sup>t</sup> en arrière avec des poussées de carre, arrêt dérapage',
            'Enchaîn<sup>t</sup> de Croisés ou Courus en avant sur un cercle dans un sens puis dans l’autre (au moins 4 croisés par cercle)',
            'Enchaîn<sup>t</sup> de fentes latérales paBnées en dehors avant et en lobe avec port(s) de bras',
            'Enchaîn<sup>t</sup> de Fentes latérales arrière (4 au moins) sur un cercle dans un sens puis dans l’autre, avec port (s) de bras ou maintien simple des bras',
            'Une cigogne en arrière sur chaque pied ≥ 3 secondes avec maintien des bras',
            'Fente en avant ou glissade sur un genou avec port de bras puis révérence (Fille) ou Salut (Garçon)'
        ],
        'Lame 4' => [
            'Enchaîn<sup>t</sup> de chassés en avant sur un cercle, terminé par un dehors avant tenu au moins 3 secondes ; avec port de bras ou maintien des bras, dans un sens puis dans l’autre',
            'Retourn<sup>t</sup> glissé sur 2 pieds d’avant en arrière en courbe',
            'Retourn<sup>t</sup> glissé sur 2 pieds d’arrière en avant',
            'Saut d’un ½ tour. Avec appel et réception sur 2 pieds en glissant',
            'Pirouette solo de 2 rotations minimum sur 2 pieds',
            'Enchaîn<sup>t</sup> de croisés en arrière sur un cercle dans un sens puis dans l’autre'
        ],
        'Lame 5' => [
            'En musique: Enchaîn<sup>t</sup> d’un chassé suivi d’un croisé (ou couru) en avant sur un cercle dans un sens puis dans l’autre terminé par une sortie avec port de bras et de tête.',
            'En musique: Enchaîn<sup>t</sup> de chassés en arrière paBnés sur un cercle dans un sens puis dans l’autre, terminé par un long dehors arrière avec port de bras',
            'Arabesque en avant et en carre maintenue au moins 3 secondes avec port de bras',
            '1 Trois en dehors sur 1 pied, départ à l’arrêt et carre de sortie maintenue; dans un sens puis dans l’autre',
            '2 pas de géant terminés par un freinage parallèle ou freinage sur une carre (Arrêt complet)',
            '1 saut de valse'
        ],
        'Lame 6' => [
            'En musique: Longueur 1 : Enchaîn<sup>t</sup> de 4 lobes composés de Chassé/ Balancé en dehors avant, effectués alternativ<sup>t</sup> sur un pied puis sur l’autre',
            'En musique: Longueur 2 : Enchaîn<sup>t</sup> de 4 lobes composés de chassés ou courus / dehors en arrière réalisés alternativ<sup>t</sup> sur un pied puis sur l’autre. Le tout finit par un freinage en dedans arrière ou une sortie avec port de bras.',
            'AItude en carre (Sur un pied, aigle, fente INA...)',
            'PiroueJe de 2 tours minimum sur un pied',
            'Un ½ Flip',
        ],
        'Lame 7' => [
            'En musique: Enchaîn<sup>t</sup> de croisés ou courus suivi d’un dedans en avant patinés en lobes alternés ( 2 lobes minimum terminés par une sortie avec port de bras)',
            'Attitude en carre sur chaque pied (A l’exception de l’arabesque) avec jambe libre maintenue > de l’horizontale 3 secondes minimum',
            'Enchaîn<sup>t</sup> de cross rolls en avant',
            'Une cafetière en avant ou en arrière',
            'PiroueJe solo de 3 rotations sur 1 pied avec sortie sur 1 pied',
            'Saut de trois sur chaque pied'
        ],
        'Lame 8' => [
            'Enchaîn<sup>t</sup> de 2 trois minimum en dehors avant terminé par une sortie avec port de bras',
            'Enchaîn<sup>t</sup> de 4 lobes en dehors avant puis 4 lobes en dedans avant patinés sur un axe matérialisé. (Déplac<sup>t</sup> Aller/Retour)',
            'Exécution d’un Mohawk ouvert en dedans avec sortie glissée sur 1 pied puis sur l’autre',
            'Enchaîn<sup>t</sup> de 2 lobes minimum composés d’un croisé en arrière suivi d’un dehors arrière avec port de bras',
            'Pirouette debout démarrée en dehors arrière avec 2 rotations minimum sur 1 pied',
            'Saut d’un tour (Au choix)',
        ]
    ];
    $etapes = $etapes_descr[$impression['niveau']];

    $largeur = 630;
    $hauteur = 30;
    $idx_width = 10;
    $name_width = 80;
    $result_width = 30;
    $etape_width = floor(($largeur - $name_width - $result_width) / count($etapes));
@endphp
<table class="adherents" style="width:{{ $largeur }}px" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th style="width:{{ $idx_width + $name_width }}px" colspan="2"><div>Prénom NOM</div></th>
            @foreach ($etapes as $etape)
                <th style="width:{{ $etape_width }}px"><div>{!! $etape !!}</div></th>
            @endforeach
            <th style="width:{{ $result_width }}px"><div>Résultat oui ou non</div></th>
        </tr>
    </thead>
    <tbody>
        @foreach (range(0, 23) as $i)
            @php
                $personne = null;
                $adhesion = null;
                if (isset($impression['adherents'][$i]))
                {
                    $personne = $impression['adherents'][$i];
                    $adhesion = $personne->adhesions[0];
                }
            @endphp
            <tr>
                <td class="centered" style="height:{{$hauteur}}px; width:{{$idx_width}}px;">
                    <div>{{ $i + 1 }}</div>
                </td>
                <td style="height:{{$hauteur}}px; width:{{$name_width}}px; {{ (isset($personne) && !isset($personne->niveau)) ? "background-color: #dddddd;" : "" }}">
                    <div>{{ $personne?->prenom }} {{ $personne?->nom }}</div>
                </td>
                @foreach ($etapes as $etape)
                    <td style="height:{{$hauteur}}px; width:{{$etape_width}}px;"></td>
                @endforeach
                <td style="height:{{$hauteur}}px; width:{{$result_width}}px;"></td>
            </tr>
        @endforeach
    </tbody>
</table>
