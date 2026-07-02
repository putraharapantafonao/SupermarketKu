<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Pembelian Stok</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pengadaan dan transaksi stok masuk dari supplier</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('purchases.create') }}"
                   class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors text-sm shadow-sm shadow-blue-500/10 w-full sm:w-auto">
                    + Tambah Pembelian
                </a>
            </div>

            @if (session('success'))
                <div class="mt-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 mt-6 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[750px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kode PO</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Supplier</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Petugas</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Total</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Tanggal</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($purchases as $purchase)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-5 py-4 text-sm font-mono font-medium text-blue-600 dark:text-blue-400">
                                    {{ $purchase->purchase_code }}
                                </td>
                                <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $purchase->supplier->name }}
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    {{ $purchase->user->name }}
                                </td>
                                <td class="px-5 py-4 text-sm text-right font-semibold font-mono text-gray-900 dark:text-white">
                                    Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-sm whitespace-nowrap font-mono">
                                    {{ $purchase->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-sm text-center">
                                    <a href="{{ route('purchases.show', $purchase->id) }}"
                                       class="inline-block bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
