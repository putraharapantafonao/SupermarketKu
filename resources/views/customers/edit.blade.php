<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Pelanggan" subtitle="Perbarui profil atau informasi member SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <x-card>

            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <x-input-group label="Nama Pelanggan" name="name" type="text" :value="old('name', $customer->name)" :error="$errors->first('name')" placeholder="Masukkan nama lengkap pelanggan..." required />

                <x-input-group label="No HP / Kontak" name="phone" type="text" :value="old('phone', $customer->phone)" :error="$errors->first('phone')" placeholder="Contoh: 081234567xxx..." />

                <x-input-group label="Email (Opsional)" name="email" type="email" :value="old('email', $customer->email)" :error="$errors->first('email')" placeholder="Contoh: pelanggan@email.com..." />

                <x-input-group label="Alamat Lengkap" name="address" type="textarea" :value="old('address', $customer->address)" :error="$errors->first('address')" placeholder="Tulis alamat rumah atau tempat tinggal pelanggan..." />

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Update Pelanggan</x-button>
                    <x-button variant="secondary" href="{{ route('customers.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
