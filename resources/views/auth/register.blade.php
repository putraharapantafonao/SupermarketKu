<x-guest-layout>
    <div class="fixed inset-0 min-h-screen flex flex-col justify-center items-center bg-gray-100 dark:bg-gray-950 px-4 z-50">

        <div class="w-full sm:max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 sm:p-8 rounded-2xl shadow-2xl transition-all">

            <div class="flex flex-col items-center mb-6">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center shadow-md shadow-blue-500/20 mb-3">
                    <span class="text-2xl text-white">🛍️</span>
                </div>
                <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Daftar Staf Baru</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Buat akun untuk masuk ke manajemen SupermarketKu</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label center for="name" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required border autofocus autocomplete="name"
                           placeholder="masukkan nama lengkap Anda..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alamat Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                           placeholder="contoh: nama@toko.com"
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           placeholder="minimal 8 karakter..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="password_confirmation" class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           placeholder="ulangi kata sandi..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-500/10 active:scale-[0.98]">
                        Daftarkan Akun Baru
                    </button>
                </div>
            </form>

            <div class="relative flex py-4 items-center">
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 font-medium">Sudah Punya Akun?</span>
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
            </div>

            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Sudah terdaftar sebagai staf?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        Masuk 
                    </a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>
