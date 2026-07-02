<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Promo</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat program diskon atau pemotongan harga baru di SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('promotions.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Program Promo</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Promo JSM Gajian, Diskon Akhir Tahun..."
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror">
                        @error('name') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Target Produk</label>
                        <select name="product_id" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">✨ Semua Produk (Global Toko)</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe Potongan Harga</label>
                        <select name="type" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>🔢 Persentase (%)</option>
                            <option value="nominal" {{ old('type') == 'nominal' ? 'selected' : '' }}>💵 Nominal Rupiah (Rp)</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Besaran Nilai Promo</label>
                        <input type="number" name="value" value="{{ old('value') }}" placeholder="Contoh: 10 untuk persen, 5000 untuk rupiah..."
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('value') border-red-500 focus:ring-red-500 @enderror">
                        @error('value') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                    </div>

                    <div class="hidden md:block"></div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Mulai Berlaku</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 focus:ring-red-500 @enderror">
                        @error('start_date') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Selesai Promo</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 focus:ring-red-500 @enderror">
                        @error('end_date') <span class="text-xs text-red-500 mt-1">❌ {{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="flex items-center gap-3 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                        Simpan Promo
                    </button>

                    <a href="{{ route('promotions.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors text-center w-full sm:w-auto">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
