<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Dashboard SupermarketKu</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan aktivitas penjualan dan stok barang</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6 max-w-7xl mx-auto">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-md p-5 text-white transition-transform hover:scale-[1.01]">
                <p class="text-sm text-blue-100 font-medium">Penjualan Hari Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-3 counter-rupiah" data-target="{{ $todaySales }}">Rp 0</h3>
                <p class="text-xs text-blue-200 mt-2">Total omzet hari ini</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl shadow-md p-5 text-white transition-transform hover:scale-[1.01]">
                <p class="text-sm text-indigo-100 font-medium">Transaksi Hari Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-3 counter" data-target="{{ $todayTransactions }}">0</h3>
                <p class="text-xs text-indigo-200 mt-2">Jumlah nota transaksi</p>
            </div>

            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl shadow-md p-5 text-white transition-transform hover:scale-[1.01]">
                <p class="text-sm text-emerald-100 font-medium">Total Produk</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-3 counter" data-target="{{ $totalProducts }}">0</h3>
                <p class="text-xs text-emerald-200 mt-2">Variasi produk aktif</p>
            </div>

            <div class="bg-gradient-to-br from-violet-600 to-violet-700 rounded-2xl shadow-md p-5 text-white transition-transform hover:scale-[1.01]">
                <p class="text-sm text-violet-100 font-medium">Total Pelanggan</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-3 counter" data-target="{{ $totalCustomers }}">0</h3>
                <p class="text-xs text-violet-200 mt-2">Pelanggan terdaftar</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Grafik Penjualan 7 Hari Terakhir</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pergerakan omzet harian SupermarketKu</p>
                </div>
                <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs px-3 py-1 rounded-full font-semibold">
                    7 Hari
                </span>
            </div>

            <div class="w-full h-[300px] md:h-[420px] relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white"> Produk Stok Menipis</h3>
                    <span class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs px-3 py-1 rounded-full font-semibold">
                        Perlu Restok
                    </span>
                </div>

                <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                    <table class="w-full text-left border-collapse min-w-[350px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 text-sm font-semibold">Produk</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center">Stok</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center">Minimum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                            @forelse ($lowStockProducts as $product)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center font-mono text-gray-500">{{ $product->minimum_stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                        ✅ Semua stok produk dalam batas aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white"> Produk Terlaris</h3>
                    <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-3 py-1 rounded-full font-semibold">
                        Best Seller
                    </span>
                </div>

                <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                    <table class="w-full text-left border-collapse min-w-[300px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 text-sm font-semibold">Produk</th>
                                <th class="px-4 py-3 text-sm font-semibold text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                            @forelse ($bestSellingProducts as $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium">{{ $item->product->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                            {{ $item->total_sold }} pcs
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                        Belum ada data transaksi penjualan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Set opsi global Chart.js agar membaca setting dark mode & super responsif
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#1f2937' : '#f3f4f6';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($salesChart->pluck('date')),
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: @json($salesChart->pluck('total')),
                    borderWidth: 3,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.04)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#3b82f6',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Memaksa grafik mengikuti tinggi container div pembungkusnya
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'transparent' },
                        ticks: { color: textColor, font: { size: 11 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11 } }
                    }
                }
            }
        });

        // Animasi angka Counter Umum
        document.querySelectorAll('.counter').forEach(counter => {
            let target = parseInt(counter.dataset.target) || 0;
            let count = 0;
            let step = Math.ceil(target / 40) || 1;

            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                counter.innerText = count.toLocaleString('id-ID');
            }, 20);
        });

        // Animasi angka Counter Mata Uang Rupiah
        document.querySelectorAll('.counter-rupiah').forEach(counter => {
            let target = parseInt(counter.dataset.target) || 0;
            let count = 0;
            let step = Math.ceil(target / 40) || 1;

            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                counter.innerText = 'Rp ' + count.toLocaleString('id-ID');
            }, 20);
        });
    </script>
</x-app-layout>
