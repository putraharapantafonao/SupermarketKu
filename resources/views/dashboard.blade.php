<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" subtitle="Ringkasan aktivitas penjualan dan stok barang" />
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6 max-w-7xl mx-auto">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <x-card padding="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter-rupiah" data-target="{{ $todaySales }}">Rp 0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-400">Total omzet hari ini</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $todayTransactions }}">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-400">Jumlah nota transaksi</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $totalProducts }}">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-400">Variasi produk aktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pelanggan</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $totalCustomers }}">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-400">Pelanggan terdaftar</p>
            </x-card>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">
                <x-card padding="p-5">
                    <x-slot name="header">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Grafik Penjualan 7 Hari Terakhir</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pergerakan omzet harian</p>
                        </div>
                        <x-badge variant="primary">7 Hari</x-badge>
                    </x-slot>
                    <div class="w-full h-[300px] md:h-[420px] relative">
                        <canvas id="salesChart"
                            data-labels='@json($salesChart->pluck('date'))'
                            data-values='@json($salesChart->pluck('total'))'></canvas>
                    </div>
                </x-card>
            </div>

            <div>
                <x-card padding="p-5">
                    <x-slot name="header">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">Produk Stok Menipis</h3>
                        <x-badge variant="danger">Perlu Restok</x-badge>
                    </x-slot>
                    <x-table min-width="350px">
                        <x-slot name="header">
                            <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Produk</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">Stok</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">Minimum</th>
                        </x-slot>
                        @forelse ($lowStockProducts as $product)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <x-badge variant="danger">{{ $product->stock }}</x-badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                    Semua stok produk dalam batas aman.
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>

        </div>

        <x-card padding="p-5">
            <x-slot name="header">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Produk Terlaris</h3>
                <x-badge variant="success">Best Seller</x-badge>
            </x-slot>
            <x-table min-width="300px">
                <x-slot name="header">
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Produk</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-right">Terjual</th>
                </x-slot>
                @forelse ($bestSellingProducts as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $item->product->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-right">
                            <x-badge variant="success">{{ $item->total_sold }} pcs</x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                            Belum ada data transaksi penjualan.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>

    </div>

    @vite('resources/js/dashboard.js')
</x-app-layout>
