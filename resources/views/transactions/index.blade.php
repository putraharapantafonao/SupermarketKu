<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Riwayat Transaksi" subtitle="Daftar semua riwayat transaksi penjualan kasir SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <x-table minWidth="800px">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">No. Invoice</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kasir</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Pelanggan</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total Transaksi</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Metode</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal & Waktu</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                </x-slot>
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm font-mono font-bold text-blue-600 dark:text-blue-400">{{ $transaction->invoice_number }}</td>
                        <td class="px-5 py-4 text-sm font-medium">{{ $transaction->user->name }}</td>
                        <td class="px-5 py-4 text-sm">
                            @if($transaction->customer)
                                <span class="font-bold text-gray-900 dark:text-white">{{ $transaction->customer->name }}</span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 font-normal">Umum (Non-Member)</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-right font-black font-mono text-gray-900 dark:text-white">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-sm text-center">
                            <x-badge variant="primary">{{ strtoupper($transaction->payment->method ?? '-') }}</x-badge>
                        </td>
                        <td class="px-5 py-4 text-sm font-mono whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ $transaction->created_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-5 py-4 text-sm text-center">
                            <x-button variant="primary" size="sm" href="{{ route('transactions.show', $transaction->id) }}">Detail</x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada riwayat transaksi.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($transactions, 'links'))
                <div class="mt-5">{{ $transactions->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
