@props(['variant' => 'gray'])

@php
$classes = [
    'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
    'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
    'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
    'danger' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
    'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
    'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400',
];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$classes[$variant]}"]) }}>
    {{ $slot }}
</span>
