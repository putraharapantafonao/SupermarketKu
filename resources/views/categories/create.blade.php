<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tambah Kategori" subtitle="Buat kategori produk baru untuk kelompok barang SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <x-card>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
                @csrf

                <x-input-group label="Nama Kategori" name="name" type="text" :value="old('name')" :error="$errors->first('name')" placeholder="Contoh: Makanan, Minuman, Kosmetik..." required />

                <x-input-group label="Deskripsi (Opsional)" name="description" type="textarea" :value="old('description')" :error="$errors->first('description')" placeholder="Penjelasan singkat mengenai kelompok kategori barang ini..." />

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Simpan Kategori</x-button>
                    <x-button variant="secondary" href="{{ route('categories.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
