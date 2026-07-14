@props(['padding' => 'p-5 sm:p-6'])

<div {{ $attributes->merge(['class' => "bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm $padding"]) }}>
    @if(isset($header))
        <div class="flex items-center justify-between mb-5">
            {{ $header }}
        </div>
    @endif
    {{ $slot }}
    @if(isset($footer))
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif
</div>
