<x-guest-layout>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-1">Daftar</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Buat akun baru untuk mulai menggunakan SupermarketKu</p>

    <div class="relative flex items-center mb-5">
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
        <span class="flex-shrink mx-3 text-xs text-gray-400">Isi data diri Anda</span>
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="flex flex-col gap-1.5">
            <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                   placeholder="Masukkan nama lengkap"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                   placeholder="name@example.com"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="password_confirmation" class="text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="Ulangi password"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm">
                Daftar
            </button>
        </div>
    </form>

    <p class="mt-5 text-center text-sm text-gray-600 dark:text-gray-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline">
            Masuk
        </a>
    </p>
</x-guest-layout>
