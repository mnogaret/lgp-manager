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
<button id="dropdownButton-{{ $adhesion->id }}" data-dropdown-toggle="dropdown-{{ $adhesion->id }}" data-dropdown-trigger="click" type="button">
    <x-badge color="{{ $colors[$adhesion->etat] }}">{{ $adhesion->etat }}</x-badge>
</button>
<div id="dropdown-{{ $adhesion->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton-{{ $adhesion->id }}">
        @foreach ($colors as $key => $color)
            <li>
                <a href="{{ route('adhesion.changeEtat', ['adhesion' => $adhesion, 'etat' => $key ]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    <x-badge color="{{ $color }}">{{ $key }}</x-badge>
                </a>
            </li>
        @endforeach
    </ul>
</div>
<form id="change-etat-form-{{ $adhesion->id }}" action="{{ route('adhesion.changeEtat', $adhesion) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="etat" :value="$adhesion->etat">
</form>