<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Data Produk</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola produk, harga, stok, kategori, dan supplier</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('products.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors text-center text-sm shadow-sm shadow-blue-500/10">
                    + Tambah Produk
                </a>

                <form method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari produk / barcode..."
                           class="w-full sm:w-64 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-blue-500">

                    <button class="bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                        Cari
                    </button>
                </form>
            </div>

            @if (session('success'))
                <div class="mt-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 mt-6 shadow-inner">
                <table class="w-full text-left border-collapse min-w-[850px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider">No</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Barcode</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Kategori</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Supplier</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-right">Harga</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-center">Stok</th>
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-4 py-3.5 text-sm">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 text-sm font-mono text-gray-400 dark:text-gray-500">{{ $product->barcode }}</td>
                                <td class="px-4 py-3.5 text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</td>
                                <td class="px-4 py-3.5 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-sm">{{ $product->supplier->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-sm text-right font-semibold font-mono text-gray-900 dark:text-white">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-sm text-center">
                                    @if ($product->stock <= $product->minimum_stock)
                                        <span class="inline-block bg-red-100 dark:bg-red-950/50 text-red-700 dark:text-red-400 px-2.5 py-0.5 rounded-full text-xs font-bold ring-1 ring-red-200 dark:ring-red-900/50">
                                            ⚠️ {{ $product->stock }} (Limit)
                                        </span>
                                    @else
                                        <span class="inline-block bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-sm text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                           class="bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded-xl text-xs font-medium transition-colors shadow-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="m-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus produk {{ $product->name }}?')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-2.5 py-1 rounded-xl text-xs font-medium transition-colors shadow-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                    Data produk belum ada atau tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
