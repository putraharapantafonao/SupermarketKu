<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Pembelian Stok" subtitle="Riwayat pengadaan dan transaksi stok masuk dari supplier" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('purchases.create') }}">+ Tambah Pembelian</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="750px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kode PO</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Supplier</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Petugas</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                </x-slot>
                @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm font-mono font-medium text-blue-600 dark:text-blue-400">{{ $purchase->purchase_code }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $purchase->supplier->name }}</td>
                        <td class="px-5 py-4 text-sm">{{ $purchase->user->name }}</td>
                        <td class="px-5 py-4 text-sm text-right font-semibold font-mono text-gray-900 dark:text-white">
                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-sm whitespace-nowrap font-mono">{{ $purchase->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm text-center">
                            <x-button variant="primary" size="sm" href="{{ route('purchases.show', $purchase->id) }}">Detail</x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data pembelian.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($purchases, 'links'))
                <div class="mt-5">{{ $purchases->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
