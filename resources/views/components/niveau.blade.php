<?php
$colors = [
    'Sans niveau' => 'white',
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
?>
@if ($niveau)
    <x-badge id="{{ $id ?? '' }}" @endif color="{{ $colors[$niveau] }}" plain="{{ $plain ?? true }}" hidden="{{ $hidden ?? false }}">{{ $niveau }}</x-badge>
@endif
