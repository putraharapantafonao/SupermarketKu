<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">💳 Checkout Pembayaran</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesaikan transaksi penjualan SupermarketKu</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-5 h-fit">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">🛒 Ringkasan Belanja</h3>

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold">Produk</th>
                            <th class="px-4 py-3 text-sm font-semibold text-center">Qty</th>
                            <th class="px-4 py-3 text-sm font-semibold text-right">Harga</th>
                            <th class="px-4 py-3 text-sm font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
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
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-5 h-fit sticky top-6">

            @if (session('error'))
                <div class="mb-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @php
                $total = collect($cart)->sum('subtotal');
            @endphp

            <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Detail Pembayaran</h3>

            <form action="{{ route('pos.processCheckout') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</label>
                    <select name="customer_id" class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Umum / Non Member</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diskon Transaksi (Rp)</label>
                    <input type="number" name="discount" value="0" min="0"
                           class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 font-mono">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode Pembayaran</label>
                    <select name="method" class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="cash">💵 Tunai</option>
                        <option value="qris">📱 QRIS</option>
                        <option value="transfer">🏦 Transfer Bank</option>
                        <option value="ewallet">💳 E-Wallet</option>
                        <option value="debit">💳 Debit Card</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Uang Bayar (Rp)</label>
                    <input type="number" name="paid_amount" value="{{ $total }}" min="0"
                           class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 font-bold font-mono text-blue-600 dark:text-blue-400">
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 font-medium">
                        <span>Total Belanja</span>
                        <span class="font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-base font-bold text-gray-900 dark:text-white pt-1">
                        <span>Total Tagihan</span>
                        <span class="text-xl font-black text-blue-600 dark:text-blue-400 font-mono">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-sm shadow-green-500/10 transition-colors">
                        Simpan Transaksi 💾
                    </button>

                    <a href="{{ route('pos.index') }}"
                       class="block text-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-3 rounded-xl text-sm font-semibold transition-colors">
                        Kembali Ke Kasir
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
