<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Qashya POS')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <header class="flex items-center justify-between bg-white px-6 py-4 shadow">

        <nav class="flex gap-4 text-sm">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <a href="{{ route('products.index') }}" class="hover:underline">Produk</a>
            <a href="{{ route('history.index') }}" class="hover:underline">Riwayat</a>

            {{-- Menu Transaksi hanya muncul untuk kasir --}}
            @if (auth()->user()->hasRole('kasir'))
                <a href="{{ route('cashier.index') }}" class="hover:underline">Transaksi</a>
            @endif

            {{-- Menu Stok, Laporan, dan Pengguna hanya muncul untuk admin --}}
            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('stock.index') }}" class="hover:underline">Stok</a>
                <a href="{{ route('reports.index') }}" class="hover:underline">Laporan</a>
                <a href="{{ route('users.index') }}" class="hover:underline">Pengguna</a>
            @endif
        </nav>

        <div class="flex items-center gap-4 text-sm">
            <span>{{ auth()->user()->name }} ({{ auth()->user()->role?->label }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Keluar</button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-6xl p-6">
        @if (session('status'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>