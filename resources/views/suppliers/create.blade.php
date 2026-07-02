<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Supplier</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan data pemasok barang baru untuk SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Supplier</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Sumber Rezeki, CV. Maju Jaya..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror">
                    @error('name')
                        <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No HP / Kontak</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567xxx..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 focus:ring-red-500 @enderror">
                    @error('phone')
                        <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat Lengkap</label>
                    <textarea name="address" rows="4" placeholder="Tulis alamat operasional kantor atau gudang supplier..."
                              class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 focus:ring-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                        Simpan Supplier
                    </button>

                    <a href="{{ route('suppliers.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors text-center w-full sm:w-auto">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
