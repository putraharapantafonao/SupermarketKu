<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Laporan Penjualan" subtitle="Rekap transaksi penjualan per periode" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card padding="p-4">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal Mulai</label>
                        <input type="date"
                            name="start_date"
                            value="{{ $start }}"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal Selesai</label>
                        <input type="date"
                            name="end_date"
                            value="{{ $end }}"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all">
                    </div>
                </div>

                <div class="flex flex-wrap items-end gap-2 pt-2 lg:pt-0">
                    <x-button variant="primary" size="default">Filter</x-button>

                    <x-button variant="danger" href="{{ route('reports.sales.pdf', request()->all()) }}" size="default">PDF</x-button>

                    <x-button variant="success" href="{{ route('reports.sales.excel', request()->all()) }}" size="default">Excel</x-button>
                </div>

            </form>
        </x-card>

        <x-table min-width="600px">
            <x-slot name="header">
                <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Invoice</th>
                <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kasir</th>
                <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Pelanggan</th>
                <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal</th>
                <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total</th>
            </x-slot>

            @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-5 py-4 text-sm font-mono font-medium text-primary-600 dark:text-primary-400">
                        {{ $transaction->invoice_number }}
                    </td>
                    <td class="px-5 py-4 text-sm">
                        {{ $transaction->user->name }}
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <x-badge variant="{{ $transaction->customer ? 'indigo' : 'gray' }}">
                            {{ $transaction->customer->name ?? 'Umum' }}
                        </x-badge>
                    </td>
                    <td class="px-5 py-4 text-sm whitespace-nowrap">
                        {{ $transaction->created_at->format('d-m-Y H:i') }}
                    </td>
                    <td class="px-5 py-4 text-sm font-semibold text-right text-gray-900 dark:text-white">
                        Rp {{ number_format($transaction->grand_total,0,',','.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Tidak ada data penjualan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="flex justify-end">
            <div class="w-full sm:w-auto bg-primary-50 dark:bg-primary-950/40 border border-primary-100 dark:border-primary-900/60 rounded-2xl p-5 shadow-sm text-right">
                <span class="block text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest mb-1">Akumulasi Pendapatan</span>
                <span class="text-2xl sm:text-3xl font-black text-primary-700 dark:text-primary-400">
                    Rp {{ number_format($total,0,',','.') }}
                </span>
            </div>
        </div>

    </div>
</x-app-layout>
