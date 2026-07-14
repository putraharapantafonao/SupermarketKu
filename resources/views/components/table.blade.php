@props(['minWidth' => '600px'])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900']) }}>
    <table class="w-full text-left border-collapse" style="min-width: {{ $minWidth }}">
        @if(isset($header))
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                    {{ $header }}
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
            <tfoot class="bg-gray-50 dark:bg-gray-800/60 border-t border-gray-200 dark:border-gray-800">
                <tr>
                    {{ $footer }}
                </tr>
            </tfoot>
        @endif
    </table>
</div>
