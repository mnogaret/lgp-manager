<?php
$colors = [
    'Sans niveau' => 'white',
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
?>
@if ($niveau)
    <x-badge id="{{ $id ?? '' }}" @endif color="{{ $colors[$niveau] }}" plain="{{ $plain ?? true }}" hidden="{{ $hidden ?? false }}">{{ $niveau }}</x-badge>
@endif
