<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tambah Pegawai" subtitle="Daftarkan akun kasir atau administrator baru ke dalam sistem" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <x-card>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf

                <x-input-group label="Nama Lengkap" name="name" type="text" :value="old('name')" :error="$errors->first('name')" placeholder="Masukkan nama lengkap staf..." required />

                <x-input-group label="Alamat Email (Username)" name="email" type="email" :value="old('email')" :error="$errors->first('email')" placeholder="Contoh: kasir1@supermarketku.com..." required />

                <x-input-group label="Hak Akses Sistem (Role)" name="role_id" type="select">
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Owner</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Kasir</option>
                    <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Gudang</option>
                </x-input-group>

                <x-input-group label="Password Akun" name="password" type="password" :error="$errors->first('password')" placeholder="Minimal 8 karakter unik..." required />

                <x-input-group label="Ulangi Password" name="password_confirmation" type="password" placeholder="Ketik ulang password di atas..." />

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Simpan Akun</x-button>
                    <x-button variant="secondary" href="{{ route('users.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
