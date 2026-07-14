<x-guest-layout>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-1">Verifikasi Email</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang kami kirimkan.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm text-green-600 text-center bg-green-50 dark:bg-green-900/20 rounded-xl px-4 py-3">
            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf

        <div>
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm">
                Kirim Ulang Email Verifikasi
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
