<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tambah Pembelian Stok" subtitle="Catat transaksi pengadaan stok barang masuk dari supplier" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <x-card>

            <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="max-w-md">
                    <x-input-group label="Supplier / Pemasok" name="supplier_id" type="select" :error="$errors->first('supplier_id')" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </x-input-group>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800/60 pt-4">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white mb-4">Daftar Produk Masuk</h3>
                </div>

                <div id="items" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50/50 dark:bg-gray-800/20 p-3 rounded-2xl border border-gray-100 dark:border-gray-800/40 relative">
                        <x-input-group label="Produk" name="product_id[]" type="select">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </x-input-group>

                        <x-input-group label="Jumlah" name="quantity[]" type="number" placeholder="Qty / Jumlah Masuk" />

                        <x-input-group label="Harga Beli Satuan" name="purchase_price[]" type="number" placeholder="Harga Beli Satuan (Rp)" />
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="addItem()"
                            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        + Tambah Baris Produk
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Simpan Pembelian</x-button>
                    <x-button variant="secondary" href="{{ route('purchases.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>

    <script>
        function addItem() {
            let html = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50/50 dark:bg-gray-800/20 p-3 rounded-2xl border border-gray-100 dark:border-gray-800/40 relative">
                    <div class="flex flex-col gap-1">
                        <select name="product_id[]" class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <input type="number" name="quantity[]" placeholder="Qty / Jumlah Masuk" min="1"
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <input type="number" name="purchase_price[]" placeholder="Harga Beli Satuan (Rp)" min="0"
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            `;
            document.getElementById('items').insertAdjacentHTML('beforeend', html);
        }
    </script>
</x-app-layout>
