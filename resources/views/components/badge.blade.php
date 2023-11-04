<?php
$colors = [
    'default' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'dark' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    'red' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
    'yellow' => 'bg-yellow-900 text-white dark:bg-yellow-500 dark:text-black',
    'green' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
    'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
    'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-200',
    'pink' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
    'white' => 'bg-gray-100 text-gray-800 dark:bg-gray-400 dark:text-gray-900',
    'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-200',
    'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
    'brown' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400',
    'black' => 'bg-gray-900 text-white dark:bg-black dark:text-gray-400',
];
$color = $color ?? 'default';
$colorClasses = $colors[$color] ?? $colors['default'];
?>
<span class="text-sm font-medium mr-2 ml-2 px-2.5 py-0.5 rounded {{ $colorClasses }}">{{ $slot }}</span>
