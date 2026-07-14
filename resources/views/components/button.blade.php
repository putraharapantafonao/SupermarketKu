@props([
    'variant' => 'primary',
    'size' => 'default',
    'href' => null,
])

@php
$base = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 shadow-sm';

$variants = [
    'primary' => 'bg-primary-600 hover:bg-primary-700 text-white shadow-primary-500/10',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-none',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-500/10',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-red-500/10',
    'warning' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/10',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'default' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </button>
@endif
