@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
    @endif
</div>
