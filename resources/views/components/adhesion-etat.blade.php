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
<x-badge color="{{ $colors[$adhesion->etat] }}">{{ $adhesion->etat }}</x-badge>
