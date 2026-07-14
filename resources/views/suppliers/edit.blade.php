<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Supplier" subtitle="Perbarui informasi data pemasok barang SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <x-card>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <x-input-group label="Nama Supplier" name="name" type="text" :value="old('name', $supplier->name)" :error="$errors->first('name')" placeholder="Contoh: PT. Sumber Rezeki, CV. Maju Jaya..." required />

                <x-input-group label="No HP / Kontak" name="phone" type="text" :value="old('phone', $supplier->phone)" :error="$errors->first('phone')" placeholder="Contoh: 081234567xxx..." />

                <x-input-group label="Alamat Lengkap" name="address" type="textarea" :value="old('address', $supplier->address)" :error="$errors->first('address')" placeholder="Tulis alamat operasional kantor atau gudang supplier..." />

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Update Supplier</x-button>
                    <x-button variant="secondary" href="{{ route('suppliers.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
