<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Produk</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data produk SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6">

            <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</label>
                        <select name="category_id" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 focus:ring-red-500 @enderror">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Supplier</label>
                        <select name="supplier_id" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-500 focus:ring-red-500 @enderror">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('barcode') border-red-500 focus:ring-red-500 @enderror">
                        @error('barcode') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror">
                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('purchase_price') border-red-500 focus:ring-red-500 @enderror">
                        @error('purchase_price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('selling_price') border-red-500 focus:ring-red-500 @enderror">
                        @error('selling_price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 focus:ring-red-500 @enderror">
                        @error('stock') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Minimum</label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0"
                               class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 @error('minimum_stock') border-red-500 focus:ring-red-500 @enderror">
                        @error('minimum_stock') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Expired</label>
                        <input type="date" name="expired_date" value="{{ old('expired_date', $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('Y-m-d') : '') }}"
                               class="w-full sm:w-1/2 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 @error('expired_date') border-red-500 focus:ring-red-500 @enderror">
                        @error('expired_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-blue-500/10">
                        Update Produk
                    </button>

                    <a href="{{ route('products.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
