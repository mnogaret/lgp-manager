<?php
$colors = [
    'annulé' => 'dark',
    'liste d’attente' => 'dark',
    'créé' => 'red',
    'essai' => 'pink',
    'complet' => 'yellow',
    'réglé' => 'default',
    'validé' => 'green',
];
?>
<li class="mb-2">{{ $adhesion->groupe->nom }} <x-badge color="{{ $colors[$adhesion->etat] }}">{{ $adhesion->etat }}</x-badge></li>
