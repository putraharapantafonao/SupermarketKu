<x-guest-layout>
    <div class="fixed inset-0 min-h-screen flex flex-col justify-center items-center bg-gray-100 dark:bg-gray-950 px-4 z-50">

        <div class="w-full sm:max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl transition-all">

            <div class="flex flex-col items-center mb-6">
                <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-md border border-gray-100 dark:border-gray-700 p-2 mb-3">
                    <img src="{{ asset('images/logo-supermarketku.png') }}" alt="SupermarketKu Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">SupermarketKu</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masuk untuk memulai sesi kasir dan manajemen</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alamat Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                           placeholder="masukkan email terdaftar..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('password.request') }}">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex items-center pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0 bg-gray-50 dark:bg-gray-800">
                        <span class="ms-2 text-xs font-medium text-gray-600 dark:text-gray-400">Ingat sesi saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-500/10 active:scale-[0.98]">
                        Masuk
                    </button>
                </div>
            </form>

            <div class="relative flex py-4 items-center">
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 font-medium">Staf Baru?</span>
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
            </div>

            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Belum punya akun kepegawaian?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        Daftar 
                    </a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>
