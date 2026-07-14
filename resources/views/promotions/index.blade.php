<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Data Promo" subtitle="Kelola program diskon dan promosi produk SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <x-button variant="primary" href="{{ route('promotions.create') }}">+ Tambah Promo</x-button>
            </div>

            @if(session('success'))
                <x-alert type="success" class="mt-5">{{ session('success') }}</x-alert>
            @endif

            <x-table minWidth="800px" class="mt-6">
                <x-slot name="header">
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider w-16 text-center">No</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Nama Promo</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider">Produk Target</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-32">Tipe</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-right w-36">Nilai Potongan</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-52">Periode</th>
                    <th class="px-5 py-3.5 text-sm font-bold tracking-wider text-center w-40">Aksi</th>
                </x-slot>
                @forelse($promotions as $promotion)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-4 text-sm text-center font-mono">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $promotion->name }}</td>
                        <td class="px-5 py-4 text-sm">
                            @if($promotion->product)
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $promotion->product->name }}</span>
                            @else
                                <x-badge>Semua Produk</x-badge>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-center">
                            @if($promotion->type == 'percent')
                                <x-badge variant="indigo">Persentase</x-badge>
                            @else
                                <x-badge variant="primary">Nominal Rupiah</x-badge>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-right font-black font-mono text-gray-900 dark:text-white">
                            {{ $promotion->type == 'percent' ? $promotion->value.' %' : 'Rp '.number_format($promotion->value, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-sm text-center font-mono whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/y') }}
                        </td>
                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <x-button variant="warning" size="sm" href="{{ route('promotions.edit', $promotion->id) }}">Edit</x-button>
                                <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" class="m-0 inline">
                                    @csrf @method('DELETE')
                                    <x-button variant="danger" size="sm" data-confirm="Yakin hapus program promo {{ $promotion->name }}?" onclick="return confirm(this.dataset.confirm)">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data promo.</td>
                    </tr>
                @endforelse
            </x-table>

            @if(method_exists($promotions, 'links'))
                <div class="mt-5">{{ $promotions->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
