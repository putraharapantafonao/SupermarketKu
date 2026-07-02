<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Pegawai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan akun kasir atau administrator baru ke dalam sistem</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap staf..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror">
                    @error('name') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat Email (Username)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: kasir1@supermarketku.com..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 focus:ring-red-500 @enderror">
                    @error('email') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hak Akses Sistem (Role)</label>
                    <select name="role" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>🛒 Kasir</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}> Admin</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Password Akun</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter unik..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 focus:ring-red-500 @enderror">
                    @error('password') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ulangi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang password di atas..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                        Simpan Akun
                    </button>
                    <a href="{{ route('users.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors text-center w-full sm:w-auto">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
