<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Tambah Pembelian Stok</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Catat transaksi pengadaan stok barang masuk dari supplier</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="flex flex-col gap-1 max-w-md">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Supplier / Pemasok</label>
                    <select name="supplier_id" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 focus:ring-red-500 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800/60 pt-4">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        Daftar Produk Masuk
                    </h3>
                </div>

                <div id="items" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50/50 dark:bg-gray-800/20 p-3 rounded-2xl border border-gray-100 dark:border-gray-800/40 relative">
                        <div class="flex flex-col gap-1">
                            <select name="product_id[]" class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-1">
                            <input type="number" name="quantity[]" placeholder="Qty / Jumlah Masuk" min="1"
                                   class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex flex-col gap-1">
                            <input type="number" name="purchase_price[]" placeholder="Harga Beli Satuan (Rp)" min="0"
                                   class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="addItem()"
                            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        ➕ Tambah Baris Produk
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                        Simpan Pembelian
                    </button>

                    <a href="{{ route('purchases.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors text-center w-full sm:w-auto">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script>
        function addItem() {
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50/50 dark:bg-gray-800/20 p-3 rounded-2xl border border-gray-100 dark:border-gray-800/40 relative animate-fadeIn">
                    <div class="flex flex-col gap-1">
                        <select name="product_id[]" class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <input type="number" name="quantity[]" placeholder="Qty / Jumlah Masuk" min="1"
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex flex-col gap-1">
                        <input type="number" name="purchase_price[]" placeholder="Harga Beli Satuan (Rp)" min="0"
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            `;

            document.getElementById('items').insertAdjacentHTML('beforeend', html);
        }
    </script>
</x-app-layout>
