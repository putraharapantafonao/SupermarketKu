<x-guest-layout>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-1">Konfirmasi Password</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Masukkan password Anda untuk melanjutkan</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="Masukkan password"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm">
                Konfirmasi
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">
            Keluar
        </button>
    </form>
</x-guest-layout>
