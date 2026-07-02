<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Pembelian Stok</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Rincian nota pengadaan stok masuk SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kode PO</span>
                <span class="text-sm font-mono font-bold text-blue-600 dark:text-blue-400">{{ $purchase->purchase_code }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Supplier</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $purchase->supplier->name }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Petugas Penginput</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $purchase->user->name }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tanggal Masuk</span>
                <span class="text-sm text-gray-700 dark:text-gray-300 font-mono">{{ $purchase->created_at->format('d-m-Y H:i') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-5">
            <h3 class="font-bold text-base text-gray-900 dark:text-white mb-4">📦 Daftar Item Produk</h3>

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[550px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-24">Qty</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Harga Beli</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($purchase->details as $detail)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $detail->product->name }}
                                </td>
                                <td class="px-5 py-4 text-sm text-center font-semibold font-mono">
                                    {{ $detail->quantity }}
                                </td>
                                <td class="px-5 py-4 text-sm text-right font-mono">
                                    Rp {{ number_format($detail->purchase_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-sm text-right font-bold text-gray-900 dark:text-white font-mono">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 mt-6 pt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <a href="{{ route('purchases.index') }}"
                   class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors text-center order-2 sm:order-1 w-full sm:w-auto">
                    ↩️ Kembali
                </a>

                <div class="bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/60 rounded-2xl px-5 py-3.5 text-right order-1 sm:order-2 w-full sm:w-auto">
                    <span class="block text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-0.5">Total Pengeluaran</span>
                    <span class="text-xl font-black text-blue-700 dark:text-blue-400 font-mono">
                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                    </span>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
