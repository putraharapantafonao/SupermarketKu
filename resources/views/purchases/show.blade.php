<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Detail Pembelian Stok" subtitle="Rincian nota pengadaan stok masuk SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-5xl mx-auto space-y-6">

        <x-card>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kode PO</span>
                    <span class="text-sm font-mono font-bold text-primary-600 dark:text-primary-400">{{ $purchase->purchase_code }}</span>
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
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="font-bold text-base text-gray-900 dark:text-white">Daftar Item Produk</h3>
            </x-slot>

            <x-table min-width="550px">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-24">Qty</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Harga Beli</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right">Subtotal</th>
                </x-slot>

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
            </x-table>

            <x-slot name="footer">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <x-button variant="secondary" href="{{ route('purchases.index') }}" size="default">Kembali</x-button>

                    <div class="bg-primary-50 dark:bg-primary-950/40 border border-primary-100 dark:border-primary-900/60 rounded-2xl px-5 py-3.5 text-right w-full sm:w-auto">
                        <span class="block text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest mb-0.5">Total Pengeluaran</span>
                        <span class="text-xl font-black text-primary-700 dark:text-primary-400 font-mono">
                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </x-slot>
        </x-card>

    </div>
</x-app-layout>
