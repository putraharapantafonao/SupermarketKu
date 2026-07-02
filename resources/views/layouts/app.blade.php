<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SupermarketKu</title>

    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo-supermarketku.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen">

    <div class="min-h-screen flex flex-col lg:flex-row">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-4 sm:px-6 py-4 flex flex-col sm:flex-row gap-4 justify-between items-center mt-16 lg:mt-0 shadow-sm">

                <div class="w-full sm:w-auto text-left">
                    {{ $header ?? '' }}
                </div>

                <div class="w-full sm:w-auto flex flex-wrap items-center justify-between sm:justify-end gap-3">

                    <button onclick="toggleDarkMode()"
                        class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-2 rounded-xl text-sm font-medium transition-colors">
                        🌓 Mode
                    </button>

                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                            @csrf
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-red-500/10">
                                Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-950 min-h-[calc(100vh-73px)]">
                {{ $slot }}
            </main>

        </div>
    </div>

    <script>
        // Cek localStorage sebelum render untuk menghindari flash screen putih
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');

            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }
    </script>
</body>
</html>
