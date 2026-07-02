<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Notifikasi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau peringatan stok kritis dan masa kedaluwarsa produk SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                Peringatan Stok Menipis
            </h3>

            <div class="space-y-2">
                @forelse ($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-3.5 bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40 rounded-xl">
                        <span class="text-sm font-semibold text-amber-900 dark:text-amber-300">{{ $product->name }}</span>
                        <span class="bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200 text-xs px-2.5 py-1 rounded-lg font-bold font-mono">
                            Sisa {{ $product->stock }} unit
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500 py-2">✅ Semua stok produk dalam kondisi aman.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                Produk Hampir Expired (30 Hari Ke Depan)
            </h3>

            <div class="space-y-2">
                @forelse ($almostExpiredProducts as $product)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3.5 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/40 rounded-xl gap-2">
                        <span class="text-sm font-semibold text-orange-900 dark:text-orange-300">{{ $product->name }}</span>
                        <span class="text-xs text-orange-700 dark:text-orange-400 font-mono font-medium">
                            Kadaluarsa: {{ \Carbon\Carbon::parse($product->expired_date)->format('d-m-Y') }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500 py-2">✅ Tidak ada produk yang mendekati masa kadaluarsa.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                Produk Sudah Expired
            </h3>

            <div class="space-y-2">
                @forelse ($expiredProducts as $product)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3.5 bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 rounded-xl gap-2 animate-pulse">
                        <span class="text-sm font-bold text-red-900 dark:text-red-300">❌ {{ $product->name }}</span>
                        <span class="bg-red-100 dark:bg-red-900/60 text-red-800 dark:text-red-200 text-xs px-2.5 py-1 rounded-lg font-bold font-mono">
                            Selesai: {{ \Carbon\Carbon::parse($product->expired_date)->format('d-m-Y') }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500 py-2">✅ Bagus! Tidak ada produk kadaluarsa yang tertinggal di rak.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
