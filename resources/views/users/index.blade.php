<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Manajemen Pegawai</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data hak akses pengguna, kasir, dan petugas sistem SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors text-sm shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                    + Tambah User Baru
                </a>
            </div>

            @if (session('success'))
                <div class="mt-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 mt-6 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama Pengguna</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Email</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Role Hak Akses</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-44">Terdaftar Pada</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-sm font-mono">{{ $user->email }}</td>

                                <td class="px-5 py-4 text-sm text-center">
                                    @if($user->role == 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400">
                                             Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400">
                                            🛒 Kasir
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm font-mono text-gray-400 dark:text-gray-500">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-5 py-4 text-sm text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-xl text-xs font-medium transition-colors shadow-sm">
                                            Edit
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Yakin ingin mencabut akses dan menghapus user {{ $user->name }}?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-xl text-xs font-medium transition-colors shadow-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
