<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Checkout Pembayaran" subtitle="Selesaikan transaksi penjualan SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

        <div class="lg:col-span-2">
            <x-card>
                <x-slot name="header">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Ringkasan Belanja</h3>
                </x-slot>

                <x-table min-width="500px">
                    <x-slot name="header">
                        <th class="px-4 py-3 text-sm font-semibold">Produk</th>
                        <th class="px-4 py-3 text-sm font-semibold text-center">Qty</th>
                        <th class="px-4 py-3 text-sm font-semibold text-right">Harga</th>
                        <th class="px-4 py-3 text-sm font-semibold text-right">Subtotal</th>
                    </x-slot>

                    @foreach ($cart as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium">{{ $item['name'] }}</td>
                            <td class="px-4 py-3.5 text-sm text-center font-semibold">{{ $item['quantity'] }}</td>
                            <td class="px-4 py-3.5 text-sm text-right font-mono">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-sm text-right font-semibold text-gray-900 dark:text-white font-mono">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            </x-card>
        </div>

        <div class="sticky top-6">
            <x-card>
                @if (session('error'))
                    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
                @endif

                @php
                    $total = collect($cart)->sum('subtotal');
                @endphp

                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Detail Pembayaran</h3>

                <form action="{{ route('pos.processCheckout') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</label>
                        <select name="customer_id" class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">Umum / Non Member</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diskon Transaksi (Rp)</label>
                        <input type="number" name="discount" value="0" min="0"
                               class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 font-mono">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode Pembayaran</label>
                        <select name="method" class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="cash">Tunai</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="debit">Debit Card</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Uang Bayar (Rp)</label>
                        <input type="number" name="paid_amount" value="{{ $total }}" min="0"
                               class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 font-bold font-mono text-primary-600 dark:text-primary-400">
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 font-medium">
                            <span>Total Belanja</span>
                            <span class="font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-base font-bold text-gray-900 dark:text-white pt-1">
                            <span>Total Tagihan</span>
                            <span class="text-xl font-black text-primary-600 dark:text-primary-400 font-mono">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2">
                        <x-button variant="success" class="w-full text-center" size="lg">Simpan Transaksi</x-button>

                        <x-button variant="secondary" href="{{ route('pos.index') }}" class="w-full text-center" size="default">Kembali Ke Kasir</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
