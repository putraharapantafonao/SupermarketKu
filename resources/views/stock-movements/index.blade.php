<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Riwayat Stok</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Log mutasi pergerakan stok barang masuk dan keluar SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[650px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-32">Tipe</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Jumlah</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Keterangan</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-44">Tanggal Log</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($stockMovements as $movement)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $movement->product->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-center">
                                    @if ($movement->type == 'in')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                                            📥 Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400">
                                            📤 Keluar
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm text-center font-black font-mono text-gray-900 dark:text-white">
                                    {{ $movement->quantity }}
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    {{ $movement->description ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-sm whitespace-nowrap font-mono text-gray-500">
                                    {{ $movement->created_at->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $stockMovements->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
