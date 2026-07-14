<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Data Kategori" subtitle="Kelola kategori produk SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('categories.create') }}">+ Tambah Kategori</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="500px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Deskripsi</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                </x-slot>
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-5 py-4 text-sm">{{ $category->description ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('categories.edit', $category->id) }}">Edit</x-button>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <x-button variant="danger" size="sm" data-confirm="Yakin hapus kategori {{ $category->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($categories, 'links'))
                <div class="mt-5">{{ $categories->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
