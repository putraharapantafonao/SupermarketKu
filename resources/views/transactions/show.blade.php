<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1 no-print">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Struk</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Invoice {{ $transaction->invoice_number }}</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-5 sm:p-6 print:border-none print:shadow-none print:p-0">

            <div class="text-center space-y-1">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">SupermarketKu</h2>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Struk Pembelian Resmi</p>
            </div>

            <div class="border-t border-b border-gray-100 dark:border-gray-800 py-4 my-5 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
                <p><span class="font-medium text-gray-400">Invoice:</span> <strong class="font-mono text-gray-900 dark:text-white">{{ $transaction->invoice_number }}</strong></p>
                <p><span class="font-medium text-gray-400">Kasir:</span> <strong class="text-gray-900 dark:text-white">{{ $transaction->user->name }}</strong></p>
                <p><span class="font-medium text-gray-400">Pelanggan:</span> <strong class="text-gray-900 dark:text-white">{{ $transaction->customer->name ?? 'Umum (Non-Member)' }}</strong></p>
                <p><span class="font-medium text-gray-400">Tanggal:</span> <strong class="font-mono text-gray-900 dark:text-white">{{ $transaction->created_at->format('d-m-Y H:i') }}</strong></p>
            </div>

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner">
                <table class="w-full text-left border-collapse min-w-[450px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-4 py-3 text-sm font-bold">Produk</th>
                            <th class="px-4 py-3 text-sm font-bold text-center w-20">Qty</th>
                            <th class="px-4 py-3 text-sm font-bold text-right w-36">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($transaction->details as $detail)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $detail->product->name }}</td>
                                <td class="px-4 py-3 text-sm text-center font-mono font-semibold">{{ $detail->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-right font-mono font-semibold">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 mt-5 pt-4 space-y-2.5 text-sm text-gray-600 dark:text-gray-400 font-medium">
                <div class="flex justify-between">
                    <span>Total Item</span>
                    <span class="font-mono">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>

                @if($transaction->discount > 0)
                    <div class="flex justify-between text-red-600 dark:text-red-400">
                        <span>Diskon Transaksi</span>
                        <span class="font-mono">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($transaction->tax > 0)
                    <div class="flex justify-between">
                        <span>Pajak</span>
                        <span class="font-mono">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center text-base font-bold text-gray-900 dark:text-white pt-2 border-t border-dashed border-gray-200 dark:border-gray-800">
                    <span>Grand Total</span>
                    <span class="text-xl font-black text-blue-600 dark:text-blue-400 font-mono">
                        Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between pt-1">
                    <span>Jumlah Bayar</span>
                    <span class="font-mono">Rp {{ number_format($transaction->payment->paid_amount ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Uang Kembalian</span>
                    <span class="font-mono text-emerald-600 dark:text-emerald-400 font-bold">Rp {{ number_format($transaction->payment->change_amount ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 no-print">

                <a href="{{ route('transactions.receipt', $transaction->id) }}"
                   target="_blank"
                   class="text-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors shadow-sm shadow-emerald-500/10 inline-flex items-center justify-center">
                    Cetak Struk
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="text-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors inline-flex items-center justify-center">
                    Kembali
                </a>
            </div>

        </div>
    </div>

    @if(session('print_on_load'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                let thermalWindow = window.open("{{ route('transactions.receipt', $transaction->id) }}", '_blank');
                if (thermalWindow) {
                    thermalWindow.focus();
                }
            });
        </script>
    @endif

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
                color: black !important;
            }
        }
    </style>
</x-app-layout>
