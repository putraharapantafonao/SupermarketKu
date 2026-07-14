<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Data Supplier" subtitle="Kelola data pemasok barang SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('suppliers.create') }}">+ Tambah Supplier</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="650px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">No HP</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Alamat</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                </x-slot>
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                        <td class="px-5 py-4 text-sm font-mono">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm max-w-xs truncate" title="{{ $supplier->address }}">{{ $supplier->address ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('suppliers.edit', $supplier->id) }}">Edit</x-button>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <x-button variant="danger" size="sm" data-confirm="Yakin hapus supplier {{ $supplier->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data supplier.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($suppliers, 'links'))
                <div class="mt-5">{{ $suppliers->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
