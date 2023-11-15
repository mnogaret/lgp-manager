<?php
$colors = [
    'default' => 'border border-blue-100 bg-blue-100 text-blue-800 dark:border-blue-900 dark:bg-blue-900 dark:text-blue-300',
    'dark' => 'border border-gray-100 bg-gray-100 text-gray-800 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300',
    'red' => 'border border-red-100 bg-red-100 text-red-800 dark:border-red-800 dark:bg-red-800 dark:text-red-200',
    'yellow' => 'border border-yellow-900 bg-yellow-900 text-white dark:border-yellow-500 dark:bg-yellow-500 dark:text-black',
    'green' => 'border border-green-100 bg-green-100 text-green-800 dark:border-green-800 dark:bg-green-800 dark:text-green-200',
    'indigo' => 'border border-indigo-100 bg-indigo-100 text-indigo-800 dark:border-indigo-900 dark:bg-indigo-900 dark:text-indigo-300',
    'purple' => 'border border-purple-100 bg-purple-100 text-purple-800 dark:border-purple-800 dark:bg-purple-800 dark:text-purple-200',
    'pink' => 'border border-pink-100 bg-pink-100 text-pink-800 dark:border-pink-900 dark:bg-pink-900 dark:text-pink-300',
    'white' => 'border border-gray-100 bg-gray-100 text-gray-800 dark:border-gray-400 dark:bg-gray-400 dark:text-gray-900',
    'orange' => 'border border-orange-100 bg-orange-100 text-orange-800 dark:border-orange-700 dark:bg-orange-700 dark:text-orange-200',
    'blue' => 'border border-blue-100 bg-blue-100 text-blue-800 dark:border-blue-800 dark:bg-blue-800 dark:text-blue-200',
    'brown' => 'border border-yellow-100 bg-yellow-100 text-yellow-800 dark:border-yellow-900 dark:bg-yellow-900 dark:text-yellow-400',
    'black' => 'border border-white bg-black text-white',
];
$inverted_colors = [
    'default' => 'border border-blue-100 dark:border-blue-900',
    'dark' => 'border border-gray-100 dark:border-gray-700',
    'red' => 'border border-red-100 dark:border-red-800',
    'yellow' => 'border border-yellow-900 dark:border-yellow-500',
    'green' => 'border border-green-100 dark:border-green-800',
    'indigo' => 'border border-indigo-100 dark:border-indigo-900',
    'purple' => 'border border-purple-100 dark:border-purple-800',
    'pink' => 'border border-pink-100 dark:border-pink-900',
    'white' => 'border border-gray-100 dark:border-gray-400',
    'orange' => 'border border-orange-100 dark:border-orange-700',
    'blue' => 'border border-blue-100 dark:border-blue-800',
    'brown' => 'border border-yellow-100 dark:border-yellow-900',
    'black' => 'border border-gray-400 dark:border-gray-600',
];
$color = $color ?? 'default';
if ($plain ?? true) {
    $colorClasses = $colors[$color] ?? $colors['default'];
} else {
    $colorClasses = $inverted_colors[$color] ?? $inverted_colors['default'];
}
if ($hidden ?? false) {
    $colorClasses .= " hidden";
}
?>
<span id="{{ $id ?? '' }}" class="text-sm font-medium mr-2 ml-2 px-2.5 py-0.5 rounded {{ $colorClasses }}">{{ $slot }}</span>
