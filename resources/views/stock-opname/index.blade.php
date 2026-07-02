<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Stock Opname</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sinkronisasi data stok di sistem dengan stok fisik nyata di toko</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-4 sm:p-6">

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div class="w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900">
                <table class="w-full text-left border-collapse min-w-[850px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                            <th class="px-4 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kategori</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-32">Stok Sistem</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Stok Nyata</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Keterangan / Catatan</th>
                            <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                <form action="{{ route('stock-opname.update') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <td class="px-4 py-3 text-sm text-center font-mono">{{ $loop->iteration }}</td>

                                    <td class="px-5 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-5 py-3 text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center font-mono font-bold text-gray-500 dark:text-gray-400">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <input type="number" name="real_stock" value="{{ $product->stock }}" min="0"
                                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 text-center py-1.5 px-2">
                                    </td>

                                    <td class="px-4 py-2">
                                        <input type="text" name="description" placeholder="Alasan selisih stok..."
                                               class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-blue-500 py-1.5 px-3">
                                    </td>

                                    <td class="px-5 py-2 text-center">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors shadow-sm shadow-blue-500/10">
                                            Update
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
