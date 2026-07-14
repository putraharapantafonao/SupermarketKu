<x-guest-layout>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-1">Lupa Password</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Masukkan email Anda untuk menerima link reset password</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                   placeholder="name@example.com"
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm">
                Kirim Link Reset Password
            </button>
        </div>
    </form>

    <p class="mt-5 text-center text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('login') }}" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline">
            Kembali ke halaman masuk
        </a>
    </p>
</x-guest-layout>
