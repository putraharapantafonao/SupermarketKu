<div class="lg:hidden fixed top-0 left-0 z-50 p-4 w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo-supermarketku.png') }}" alt="Logo" class="w-12 h-12 rounded-lg object-cover">
        <span class="font-bold text-gray-800 dark:text-white">SupermarketKu</span>
    </div>
    <button id="sidebarToggle" class="p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-r border-gray-200 dark:border-gray-800 shadow-xl flex flex-col pt-16 lg:pt-0">

    <div class="hidden lg:flex p-6 border-b border-gray-200 dark:border-gray-800 items-center gap-3">
        <img src="{{ asset('images/logo-supermarketku.png') }}"
            alt="Logo SupermarketKu"
            class="w-12 h-12 rounded-xl object-cover shadow-md">
        <div>
            <h2 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent">
                SupermarketKu
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Aplikasi Kasir Modern</p>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">

        <a href="{{ route('dashboard') }}"
            class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
            {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            Dashboard
        </a>

        @if(auth()->user()->role?->name === 'Owner' || auth()->user()->role?->name === 'Owner SupermarketKu' || auth()->user()->role?->name === 'Admin' || auth()->user()->role?->name === 'Kasir')
    <a href="{{ route('pos.index') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pos.*') ? 'bg-blue-600 text-white font-bold shadow-md' : 'text-gray-400 hover:bg-gray-800/50' }}">
        <span>Kasir / POS</span>
    </a>
@endif

        @if (in_array(auth()->user()->role?->name, ['Owner', 'Admin', 'Gudang']))
            <a href="{{ route('products.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Produk
            </a>

            <a href="{{ route('categories.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Kategori
            </a>

            <a href="{{ route('suppliers.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('suppliers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Supplier
            </a>

            <a href="{{ route('purchases.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('purchases.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Pembelian Stok
            </a>

            <a href="{{ route('stock-opname.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('stock-opname.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Stock Opname
            </a>

            <a href="{{ route('stock-movements.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('stock-movements.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Riwayat Stok
            </a>
        @endif

        @if (in_array(auth()->user()->role?->name, ['Owner', 'Admin']))
            <a href="{{ route('customers.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Pelanggan
            </a>

            <a href="{{ route('promotions.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('promotions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Promo
            </a>

            <a href="{{ route('reports.sales') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Laporan
            </a>
        @endif

        @if (in_array(auth()->user()->role?->name, ['Owner', 'Admin', 'Kasir']))
            <a href="{{ route('transactions.index') }}"
                class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
                {{ request()->routeIs('transactions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                Transaksi
            </a>
        @endif

        <a href="{{ route('notifications.index') }}"
            class="block px-4 py-2.5 rounded-xl font-medium transition-all duration-200
            {{ request()->routeIs('notifications.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
            Notifikasi
        </a>
@if(auth()->user()->role?->name === 'Owner' || auth()->user()->role?->name === 'admin')
    <a href="{{ route('users.index') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/20' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800/50' }}">
        <span>Kelola Pegawai</span>
    </a>
@endif
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all group
            {{ request()->routeIs('profile.edit')
                ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-white' }}">

            <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span>Edit Profil</span>
        </a>

    </nav>
</aside>

<div id="sidebarBackdrop" class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }

    toggleBtn.addEventListener('click', toggleSidebar);
    backdrop.addEventListener('click', toggleSidebar);
</script>
