<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Data Pelanggan" subtitle="Kelola data pelanggan dan keanggotaan member SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('customers.create') }}">+ Tambah Pelanggan</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="750px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">No HP</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Email</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Alamat</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Poin</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                </x-slot>
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $customer->name }}</td>
                        <td class="px-5 py-4 text-sm font-mono">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm">{{ $customer->email ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm max-w-xs truncate" title="{{ $customer->address }}">{{ $customer->address ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            <x-badge variant="success">{{ $customer->points ?? 0 }} Poin</x-badge>
                        </td>
                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('customers.edit', $customer->id) }}">Edit</x-button>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <x-button variant="danger" size="sm" data-confirm="Yakin hapus data pelanggan {{ $customer->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data pelanggan terdaftar.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($customers, 'links'))
                <div class="mt-5">{{ $customers->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
