<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Manajemen Pegawai" subtitle="Kelola data hak akses pengguna, kasir, dan petugas sistem SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('users.create') }}">+ Tambah User Baru</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="700px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama Pengguna</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Email</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Role Hak Akses</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-44">Terdaftar Pada</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                </x-slot>
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-5 py-4 text-sm font-mono">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            @if($user->role == 'admin')
                                <x-badge variant="indigo">Admin</x-badge>
                            @else
                                <x-badge variant="primary">Kasir</x-badge>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm font-mono text-gray-400 dark:text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('users.edit', $user->id) }}">Edit</x-button>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 inline">
                                        @csrf @method('DELETE')
                                        <x-button variant="danger" size="sm" data-confirm="Yakin ingin mencabut akses dan menghapus user {{ $user->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data user.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($users, 'links'))
                <div class="mt-5">{{ $users->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
