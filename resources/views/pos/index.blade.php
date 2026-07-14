<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Kasir / POS" subtitle="Proses transaksi penjualan cepat SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-5 h-fit">

            <div class="flex flex-col md:flex-row gap-3 mb-6">
                <form method="GET" class="flex gap-2 flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama produk..."
                           class="w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm shadow-sm focus:ring-2 focus:ring-primary-500">
                    <x-button variant="secondary" size="sm">Cari</x-button>
                </form>

                <form action="{{ route('pos.scan') }}" method="POST" class="flex gap-2 flex-1">
                    @csrf
                    <input type="text" name="barcode" autofocus
                           placeholder="Scan kode barcode..."
                           class="w-full border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm shadow-sm focus:ring-2 focus:ring-primary-500">
                    <x-button variant="primary" size="sm">Scan</x-button>
                </form>
            </div>

            @if (session('success'))
                <x-alert type="success" class="mb-5">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error" class="mb-5">{{ session('error') }}</x-alert>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse ($products as $product)
                    <form action="{{ route('pos.add') }}" method="POST"
                          class="border border-gray-200 dark:border-gray-800 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-800/30 hover:shadow-md hover:border-primary-500 dark:hover:border-primary-500 transition-all flex flex-col justify-between">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-base leading-tight">{{ $product->name }}</h4>
                            <p class="text-xs font-mono text-gray-400 dark:text-gray-500 mt-1.5">BC: {{ $product->barcode }}</p>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">
                                Stok: <span class="{{ $product->stock <= 5 ? 'text-red-600 dark:text-red-400 font-bold' : '' }}">{{ $product->stock }}</span>
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-2 border-t border-gray-100 dark:border-gray-800/80 pt-3">
                            <span class="font-extrabold text-primary-600 dark:text-primary-400 text-sm sm:text-base">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </span>

                            <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                                    class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ $product->stock <= 0 ? 'Habis' : '+ Tambah' }}
                            </button>
                        </div>
                    </form>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-400 dark:text-gray-500 text-sm">
                        Produk tidak ditemukan atau data kosong.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-5 h-fit sticky top-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Keranjang Belanja
            </h3>

            <div class="space-y-3 max-h-[220px] overflow-y-auto pr-1 border-b border-gray-100 dark:border-gray-800 pb-4">
                @php $total = 0; @endphp

                @forelse ($cart as $item)
                    @php $total += $item['subtotal']; @endphp

                    <div class="border border-gray-100 dark:border-gray-800 rounded-xl p-3 bg-gray-50/30 dark:bg-gray-800/10">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm leading-tight">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            </div>

                            <form action="{{ route('pos.remove') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors">Hapus</button>
                            </form>
                        </div>

                        <div class="flex justify-between items-center mt-3 pt-2 border-t border-dashed border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-1.5">
                                <form action="{{ route('pos.decrease') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-2.5 py-1 rounded-lg text-xs font-bold transition-all">-</button>
                                </form>

                                <span class="font-bold text-sm text-gray-900 dark:text-white px-1.5">{{ $item['quantity'] }}</span>

                                <form action="{{ route('pos.increase') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-2.5 py-1 rounded-lg text-xs font-bold transition-all">+</button>
                                </form>
                            </div>

                            <p class="font-bold text-gray-900 dark:text-white text-sm">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 dark:text-gray-500 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl p-8 text-sm">
                        Keranjang masih kosong
                    </div>
                @endforelse
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                @foreach($cart as $item)
                    <input type="hidden" name="product_id[]" value="{{ $item['id'] }}">
                    <input type="hidden" name="quantity[]" value="{{ $item['quantity'] }}">
                @endforeach

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pelanggan Keanggotaan</label>
                    <select name="customer_id" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500">
                        <option value="">Umum (Non-Member)</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'No HP -' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Metode Pembayaran</label>
                    <select id="paymentMethod" name="method" class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="ewallet">E-Wallet (DANA/OVO)</option>
                        <option value="debit">Debit Card</option>
                    </select>
                </div>

                <div id="field-cash" class="payment-fields flex flex-col gap-1">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah Uang Cash Keluar</label>
                    <input type="number" name="paid_amount" min="{{ $total }}" placeholder="Masukkan total uang tunai..."
                           class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary-500">
                </div>

                <div id="field-qris" class="payment-fields hidden flex-col items-center gap-2 p-3 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-center">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Scan QRIS SupermarketKu</span>
                    <img src="{{ asset('images/qris-code.png') }}" alt="QRIS Merchant" class="w-40 h-40 object-contain bg-white p-1 rounded-xl shadow-sm border">
                    <p class="text-[10px] text-gray-400">Silakan scan kode QRIS Statis Merchant di atas.</p>
                </div>

                {{-- TODO: Pindahkan data rekening dan e-wallet ke konfigurasi (.env) sebelum production --}}
                <div id="field-transfer" class="payment-fields hidden flex-col gap-1.5 p-3 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-gray-200 dark:border-gray-700 text-xs">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Rekening Transfer Toko</span>
                    <p class="text-gray-700 dark:text-gray-300"><strong>BCA:</strong> <span class="font-mono text-primary-600 dark:text-primary-400 font-bold">8234-5678-90</span> a.n SupermarketKu</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Mandiri:</strong> <span class="font-mono text-orange-600 dark:text-orange-400 font-bold">158-00-1234-678</span> a.n SupermarketKu</p>
                </div>

                <div id="field-ewallet" class="payment-fields hidden flex-col gap-1.5 p-3 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-gray-200 dark:border-gray-700 text-xs">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Akun E-Wallet Merchant</span>
                    <p class="text-gray-700 dark:text-gray-300"><strong>DANA:</strong> <span class="font-mono font-bold text-emerald-600">0812-3456-7890</span></p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>OVO:</strong> <span class="font-mono font-bold text-purple-600">0812-3456-7890</span></p>
                </div>

                <div id="field-debit" class="payment-fields hidden flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Bank Penerbit Kartu</label>
                        <input type="text" name="card_bank" placeholder="Contoh: BRI, BNI, BCA..."
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Trace / Ref Number EDC</label>
                        <input type="text" name="trace_number" placeholder="Masukkan nomor dari struk mesin EDC..."
                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-800 pt-3 space-y-1 text-sm text-gray-600 dark:text-gray-400 font-medium">
                    <div class="flex justify-between items-center text-base font-bold text-gray-900 dark:text-white pb-2">
                        <span>Total Tagihan</span>
                        <span class="text-2xl font-black text-primary-600 dark:text-primary-400 font-mono">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2 pt-1">
                    <button type="submit" {{ count($cart) == 0 ? 'disabled' : '' }}
                            class="w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-xl text-sm font-bold transition-all shadow-sm shadow-emerald-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                        Selesaikan Transaksi
                    </button>
                </div>
            </form>

            <div class="mt-2">
                <form action="{{ route('pos.clear') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" {{ count($cart) == 0 ? 'disabled' : '' }}
                            class="w-full bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/40 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 px-4 py-2 rounded-xl text-xs font-semibold transition-colors disabled:opacity-40">
                        Kosongkan Keranjang Belanja
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('paymentMethod').addEventListener('change', function () {
        const fields = document.querySelectorAll('.payment-fields');

        fields.forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('flex');
        });

        const activeField = document.getElementById('field-' + this.value);
        if (activeField) {
            activeField.classList.remove('hidden');
            activeField.classList.add('flex');
        }
    });
</script>
