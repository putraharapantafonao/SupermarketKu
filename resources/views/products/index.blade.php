<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Data Produk" subtitle="Kelola produk, harga, stok, kategori, dan supplier" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('products.create') }}">+ Tambah Produk</x-button>

                <form method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari produk / barcode..."
                           class="w-full sm:w-64 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-primary-500">
                    <x-button variant="secondary" type="submit">Cari</x-button>
                </form>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="850px" class="mt-6">
                <x-slot name="header">
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider">No</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Barcode</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Kategori</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Supplier</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-right">Harga</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-center">Stok</th>
                    <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-center">Aksi</th>
                </x-slot>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-4 py-3.5 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3.5 text-sm font-mono text-gray-400 dark:text-gray-500">{{ $product->barcode }}</td>
                        <td class="px-4 py-3.5 text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-4 py-3.5 text-sm">
                            <x-badge variant="gray">{{ $product->category->name }}</x-badge>
                        </td>
                        <td class="px-4 py-3.5 text-sm">{{ $product->supplier->name ?? '-' }}</td>
                        <td class="px-4 py-3.5 text-sm text-right font-semibold font-mono text-gray-900 dark:text-white">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-sm text-center">
                            @if($product->stock <= $product->minimum_stock)
                                <x-badge variant="danger">{{ $product->stock }} (Limit)</x-badge>
                            @else
                                <x-badge variant="success">{{ $product->stock }}</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('products.edit', $product->id) }}">Edit</x-button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <x-button variant="danger" size="sm" data-confirm="Yakin hapus produk {{ $product->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Data produk belum ada atau tidak ditemukan.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($products, 'links'))
                <div class="mt-5">{{ $products->withQueryString()->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
