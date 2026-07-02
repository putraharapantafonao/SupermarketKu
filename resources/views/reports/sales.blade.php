<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl md:text-2xl text-gray-800 dark:text-white tracking-tight">
            📊 Laporan Penjualan
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <form method="GET" class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal Mulai</label>
                        <input type="date"
                            name="start_date"
                            value="{{ $start }}"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal Selesai</label>
                        <input type="date"
                            name="end_date"
                            value="{{ $end }}"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all">
                    </div>
                </div>

                <div class="flex flex-wrap items-end gap-2 pt-2 lg:pt-0">
                    <button type="submit" class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-blue-500/10">
                        🔍 Filter
                    </button>

                    <a href="{{ route('reports.sales.pdf', request()->all()) }}"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-red-500/10">
                        📄 PDF
                    </a>

                    <a href="{{ route('reports.sales.excel', request()->all()) }}"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-green-500/10">
                        📈 Excel
                    </a>
                </div>

            </form>
        </div>

        <div class="w-full overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm bg-white dark:bg-gray-900">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Invoice</th>
                        <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kasir</th>
                        <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Pelanggan</th>
                        <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-4 text-sm font-mono font-medium text-blue-600 dark:text-blue-400">
                                {{ $transaction->invoice_number }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                {{ $transaction->user->name }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->customer ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $transaction->customer->name ?? 'Umum' }}
                                </span>
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
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <div class="w-full sm:w-auto bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/60 rounded-2xl p-5 shadow-sm text-right">
                <span class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Akumulasi Pendapatan</span>
                <span class="text-2xl sm:text-3xl font-black text-blue-700 dark:text-blue-400">
                    Rp {{ number_format($total,0,',','.') }}
                </span>
            </div>
        </div>

    </div>
</x-app-layout>
