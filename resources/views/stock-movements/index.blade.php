<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Riwayat Stok" subtitle="Log mutasi pergerakan stok barang masuk dan keluar SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <x-table minWidth="650px">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-32">Tipe</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Jumlah</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Keterangan</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-44">Tanggal Log</th>
                </x-slot>
                @forelse($stockMovements as $movement)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $movement->product->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            @if($movement->type == 'in')
                                <x-badge variant="success">Masuk</x-badge>
                            @else
                                <x-badge variant="danger">Keluar</x-badge>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-center font-black font-mono text-gray-900 dark:text-white">{{ $movement->quantity }}</td>
                        <td class="px-5 py-4 text-sm">{{ $movement->description ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm whitespace-nowrap font-mono text-gray-500">{{ $movement->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada riwayat pergerakan stok.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($stockMovements, 'links'))
                <div class="mt-5">{{ $stockMovements->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
