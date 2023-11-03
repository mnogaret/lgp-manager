<?php
$menu = [
    [
        'icon' => 'icon-home.svg',
        'url' => '/',
        'name' => 'Dashboard',
    ],
    [
        'icon' => 'icon-form.svg',
        'url' => '/adherent',
        'name' => 'Adhérents',
    ],
/*    [
        'icon' => 'icon-stats.svg',
        'url' => '/stats',
        'name' => 'Statistiques',
    ],*/
    [
        'icon' => 'icon-admin.svg',
        'url' => '/admin',
        'name' => 'Administration',
    ],
];
?>
<nav class="border-gray-200 bg-gray-100 dark:bg-gray-800">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <x-navbar-logo />
        <div class="flex items-center md:order-2 space-x-2">
            <x-navbar-dark />
            <x-navbar-user />
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 dark:border-gray-700">
                @foreach ($menu as $link)
                    <li>
                        <x-navbar-link icon="{{ $link['icon'] }}" url="{{ $link['url'] }}" name="{{ $link['name'] }}" />
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
