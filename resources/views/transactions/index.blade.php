<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Transaksi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar semua riwayat transaksi penjualan kasir SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">No. Invoice</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kasir</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Pelanggan</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total Transaksi</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Metode</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal & Waktu</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-5 py-4 text-sm font-mono font-bold text-blue-600 dark:text-blue-400">
                                    {{ $transaction->invoice_number }}
                                </td>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 ring-1 ring-blue-100 dark:ring-blue-900/30">
                                        💳 {{ strtoupper($transaction->payment->method ?? '-') }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm font-mono whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-sm text-center">
                                    <a href="{{ route('transactions.show', $transaction->id) }}"
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors shadow-sm shadow-blue-500/10">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
