<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Stock Opname" subtitle="Sinkronisasi data stok di sistem dengan stok fisik nyata di toko" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            @if(session('success'))
                <x-alert type="success" class="mb-5">{{ session('success') }}</x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error" class="mb-5">{{ session('error') }}</x-alert>
            @endif

            <x-table minWidth="850px">
                <x-slot name="header">
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Kategori</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-32">Stok Sistem</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-36">Stok Nyata</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Keterangan / Catatan</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-28">Aksi</th>
                </x-slot>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <form action="{{ route('stock-opname.update') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <td class="px-4 py-3 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3 text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-5 py-3 text-sm">
                                <x-badge variant="gray">{{ $product->category->name }}</x-badge>
                            </td>
                            <td class="px-5 py-3 text-sm text-center font-mono font-bold text-gray-500 dark:text-gray-400">{{ $product->stock }}</td>
                            <td class="px-4 py-2 text-center">
                                <input type="number" name="real_stock" value="{{ $product->stock }}" min="0"
                                       class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary-500 text-center py-1.5 px-2">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" name="description" placeholder="Alasan selisih stok..."
                                       class="w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 py-1.5 px-3">
                            </td>
                            <td class="px-5 py-2 text-center">
                                <x-button type="submit" variant="primary" size="sm">Update</x-button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada data produk untuk stock opname.</td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
</x-app-layout>
