<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-950 px-4 py-8">

            <div class="w-full sm:max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl transition-all">

                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-gray-700 p-2 mb-3">
                        <img src="{{ asset('images/logo-supermarketku.png') }}" alt="SupermarketKu Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center">SupermarketKu</h1>
                </div>

                {{ $slot }}

                <p class="mt-6 text-center text-xs text-gray-400">SupermarketKu &mdash; Aplikasi Kasir Modern</p>

            </div>
        </div>
    </body>
</html>
