<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Akun Pegawai" subtitle="Ubah detail profil hak akses atau setel ulang kata sandi staf" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <x-card>

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <x-input-group label="Nama Lengkap" name="name" type="text" :value="old('name', $user->name)" :error="$errors->first('name')" placeholder="Masukkan nama lengkap staf..." required />

                <x-input-group label="Alamat Email (Username)" name="email" type="email" :value="old('email', $user->email)" :error="$errors->first('email')" placeholder="Contoh: kasir1@supermarketku.com..." required />

                <x-input-group label="Hak Akses Sistem (Role)" name="role_id" type="select">
                    <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Owner</option>
                    <option value="2" {{ old('role_id', $user->role_id) == 2 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role_id', $user->role_id) == 3 ? 'selected' : '' }}>Kasir</option>
                    <option value="4" {{ old('role_id', $user->role_id) == 4 ? 'selected' : '' }}>Gudang</option>
                </x-input-group>

                <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 p-3.5 rounded-xl text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                    <strong>Informasi:</strong> Biarkan kolom password di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengganti kata sandi lama milik staf/user ini.
                </div>

                <x-input-group label="Password Baru (Opsional)" name="password" type="password" :error="$errors->first('password')" placeholder="Isi hanya jika ingin diganti..." />

                <x-input-group label="Ulangi Password Baru" name="password_confirmation" type="password" placeholder="Ulangi ketik password baru..." />

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">Update Akun</x-button>
                    <x-button variant="secondary" href="{{ route('users.index') }}">Kembali</x-button>
                </div>
            </form>

        </x-card>
    </div>
</x-app-layout>
