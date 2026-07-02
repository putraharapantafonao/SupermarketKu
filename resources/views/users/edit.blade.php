<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Akun Pegawai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ubah detail profil hak akses atau setel ulang kata sandi staf</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap staf..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror">
                    @error('name') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat Email (Username)</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Contoh: kasir1@supermarketku.com..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 focus:ring-red-500 @enderror">
                    @error('email') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hak Akses Sistem (Role)</label>
                    <select name="role" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>🛒 Kasir / Petugas Toko</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>⚡ Administrator Sistem</option>
                    </select>
                </div>

                <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 p-3.5 rounded-xl text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                    ℹ️ <strong>Informasi:</strong> Biarkan kolom password di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengganti kata sandi lama milik staf/user ini.
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Isi hanya jika ingin diganti..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 focus:ring-red-500 @enderror">
                    @error('password') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ulangi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi ketik password baru..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                        Update Akun
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
