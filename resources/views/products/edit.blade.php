<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Produk" subtitle="Perbarui data produk SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto">
        <x-card>

            <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-input-group label="Kategori" name="category_id" type="select" :error="$errors->first('category_id')" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </x-input-group>

                    <x-input-group label="Supplier" name="supplier_id" type="select" :error="$errors->first('supplier_id')">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </x-input-group>

                    <x-input-group label="Barcode" name="barcode" type="text" :value="old('barcode', $product->barcode)" :error="$errors->first('barcode')" />

                    <x-input-group label="Nama Produk" name="name" type="text" :value="old('name', $product->name)" :error="$errors->first('name')" required />

                    <x-input-group label="Harga Beli (Rp)" name="purchase_price" type="number" :value="old('purchase_price', $product->purchase_price)" :error="$errors->first('purchase_price')" />

                    <x-input-group label="Harga Jual (Rp)" name="selling_price" type="number" :value="old('selling_price', $product->selling_price)" :error="$errors->first('selling_price')" />

                    <x-input-group label="Stok" name="stock" type="number" :value="old('stock', $product->stock)" :error="$errors->first('stock')" />

                    <x-input-group label="Stok Minimum" name="minimum_stock" type="number" :value="old('minimum_stock', $product->minimum_stock)" :error="$errors->first('minimum_stock')" />

                    <div class="md:col-span-2">
                        <x-input-group label="Tanggal Expired" name="expired_date" type="date" :value="old('expired_date', $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('Y-m-d') : '')" :error="$errors->first('expired_date')" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Update Produk</x-button>
                    <x-button variant="secondary" href="{{ route('products.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
