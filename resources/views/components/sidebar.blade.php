<aside class="flex w-full shrink-0 flex-col border-b border-gray-200 bg-white md:sticky md:top-0 md:h-screen md:w-64 md:border-b-0 md:border-r">
    <div class="border-b border-gray-100 px-5 py-5 md:px-6">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-900">Qashya Collection</a>
        <p class="text-xs text-gray-500">Point of Sales</p>
    </div>

    <div class="p-3 md:p-4">
        <p class="mb-3 hidden px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 md:block">Menu</p>

        <nav aria-label="Navigasi utama" class="flex gap-1 overflow-x-auto md:flex-col">
            <a href="{{ route('dashboard') }}"
                @class([
                    'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                    'bg-gray-900 text-white' => request()->routeIs('dashboard'),
                    'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('dashboard'),
                ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5 12 3l9 7.5M5.5 9v11h13V9M9 20v-6h6v6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('products.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('products.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('products.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 9 4.5-9 4.5-9-4.5L12 3Zm-9 9 9 4.5 9-4.5M3 16.5 12 21l9-4.5"/>
                    </svg>
                    <span>Kelola Produk</span>
                </a>

                <a href="{{ route('stock.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('stock.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('stock.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M8 16v-5m4 5V8m4 8v-3m4 3V6"/>
                    </svg>
                    <span>Manajemen Stok</span>
                </a>

                <a href="{{ route('users.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('users.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('users.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 4 18.5V20m6-9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7-5a3.5 3.5 0 0 1 0 7m3 2a4.5 4.5 0 0 1 3 4v1"/>
                    </svg>
                    <span>Kelola Pengguna</span>
                </a>

                <a href="{{ route('reports.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('reports.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('reports.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M8 15v-3m4 3V8m4 7V5m4 10V9"/>
                    </svg>
                    <span>Laporan Penjualan</span>
                </a>
            @elseif (auth()->user()->hasRole('kasir'))
                <a href="{{ route('cashier.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('cashier.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('cashier.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 4h2l2.2 11h10.9l2-8H6m2 12.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Zm9 0a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Z"/>
                    </svg>
                    <span>Transaksi Penjualan</span>
                </a>

                <a href="{{ route('products.index') }}"
                    @class([
                        'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                        'bg-gray-900 text-white' => request()->routeIs('products.*'),
                        'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('products.*'),
                    ])>
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3 9 4.5-9 4.5-9-4.5L12 3Zm-9 9 9 4.5 9-4.5M3 16.5 12 21l9-4.5"/>
                    </svg>
                    <span>Lihat Produk</span>
                </a>
            @endif

            <a href="{{ route('history.index') }}"
                @class([
                    'flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium',
                    'bg-gray-900 text-white' => request()->routeIs('history.*'),
                    'text-gray-700 hover:bg-gray-100' => ! request()->routeIs('history.*'),
                ])>
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12a9 9 0 1 0 2.64-6.36L3 8m0-5v5h5m4-1v5l3 2"/>
                </svg>
                <span>Riwayat Transaksi</span>
            </a>
        </nav>
    </div>

    <div class="mt-auto flex items-center justify-between border-t border-gray-100 p-4">
        <div class="flex min-w-0 items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-700">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs capitalize text-gray-500">{{ auth()->user()->role?->label }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600" aria-label="Keluar">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</aside>
