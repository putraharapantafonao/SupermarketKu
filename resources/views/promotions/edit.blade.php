<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Promo" subtitle="Perbarui informasi program diskon atau promosi SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-4xl mx-auto">
        <x-card>

            <form action="{{ route('promotions.update', $promotion->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <x-input-group label="Nama Program Promo" name="name" type="text" :value="old('name', $promotion->name)" :error="$errors->first('name')" placeholder="Contoh: Promo JSM Gajian, Diskon Akhir Tahun..." required />
                    </div>

                    <x-input-group label="Target Produk" name="product_id" type="select">
                        <option value="">Semua Produk (Global Toko)</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $promotion->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </x-input-group>

                    <x-input-group label="Tipe Potongan Harga" name="type" type="select">
                        <option value="percent" {{ old('type', $promotion->type) == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="nominal" {{ old('type', $promotion->type) == 'nominal' ? 'selected' : '' }}>Nominal Rupiah (Rp)</option>
                    </x-input-group>

                    <x-input-group label="Besaran Nilai Promo" name="value" type="number" :value="old('value', $promotion->value)" :error="$errors->first('value')" placeholder="Contoh: 10 untuk persen, 5000 untuk rupiah..." required />

                    <div class="hidden md:block"></div>

                    <x-input-group label="Tanggal Mulai Berlaku" name="start_date" type="date" :value="old('start_date', \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d'))" :error="$errors->first('start_date')" />

                    <x-input-group label="Tanggal Selesai Promo" name="end_date" type="date" :value="old('end_date', \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d'))" :error="$errors->first('end_date')" />
                </div>

                <div class="flex items-center gap-3 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Update Promo</x-button>
                    <x-button variant="secondary" href="{{ route('promotions.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
