<?php
$colors = [
    'annulé' => 'dark',
    'liste d’attente' => 'pink',
    'créé' => 'red',
    'complet' => 'yellow',
    'réglé' => 'default',
    'validé' => 'green',
];
?>
<li class="mb-2">{{ $adhesion->groupe->nom }} <x-badge color="{{ $colors[$adhesion->etat] }}">{{ $adhesion->etat }}</x-badge></li>
