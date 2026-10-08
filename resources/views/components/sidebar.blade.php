<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-screen">
    <div>
        <!-- Header Brand / Logo -->
        <div class="p-6 border-b border-gray-100">
            <h1 class="text-lg font-bold text-gray-900">Qashya Collection</h1>
            <p class="text-xs text-gray-500">Point of Sales</p>
        </div>

        <!-- Menu Navigation -->
        <div class="p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-3">Menu</p>
            
            <nav class="space-y-1">
                @if(auth()->check())
                    @if(auth()->user()->role === 'admin')
                        <!-- ================= MENU ADMIN ================= -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('products.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('products*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Kelola Produk</span>
                        </a>

                        <a href="{{ route('manajemen-stok') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('manajemen-stok*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Manajemen Stok</span>
                        </a>

                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('atur-akun*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Atur Akun</span>
                        </a>

                        <a href="{{ route('laporan-penjualan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('laporan-penjualan*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Laporan Penjualan</span>
                        </a>

                        <a href="{{ route('riwayat-transaksi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('riwayat-transaksi*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Riwayat Transaksi</span>
                        </a>

                    @elseif(auth()->user()->role === 'kasir')
                        <!-- ================= MENU KASIR ================= -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Dashboard Kasir</span>
                        </a>

                        <a href="{{ route('transaksi-penjualan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('transaksi-penjualan*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Transaksi Penjualan</span>
                        </a>

                        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('products*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Lihat Produk</span>
                        </a>

                        <a href="{{ route('riwayat-transaksi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 {{ request()->routeIs('riwayat-transaksi*') ? 'bg-gray-900 text-white hover:bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><!-- Icon --></svg>
                            <span class="font-medium">Riwayat Transaksi</span>
                        </a>
                    @endif
                @endif
            </nav>
        </div>
    </div>

    <!-- User Profile & Logout Section -->
    @auth
    <div class="p-4 border-t border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-700">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</h4>
                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </button>
        </form>
    </div>
    @endauth
</aside>