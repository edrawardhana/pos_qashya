@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header Bagian Atas -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="text-xs text-gray-400 mb-1">KF1 - Kelola data produk</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Produk</h2>
            <p class="text-sm text-gray-500">Tambah, ubah, dan hapus produk beserta harga, kategori, dan stok.</p>
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            + Tambah produk
        </a>
    </div>

    <!-- Form Filter Pencarian -->
    <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div>
            <input type="text" name="q" value="{{ request('q') }}" 
                class="w-full px-3.5 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent placeholder-gray-400 shadow-sm"
                placeholder="Cari nama, SKU, atau deskripsi...">
        </div>
        <div>
            <select name="category" onchange="this.form.submit()" 
                class="w-full px-3.5 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent text-gray-700 shadow-sm">
                <option value="">Semua kategori</option>
                @foreach ($categoryList as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="stock_status" onchange="this.form.submit()" 
                class="w-full px-3.5 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent text-gray-700 shadow-sm">
                <option value="">Semua status stok</option>
                <option value="safe" @selected(request('stock_status') === 'safe')>Aman</option>
                <option value="alert" @selected(request('stock_status') === 'alert')>Alert / Menipis</option>
            </select>
        </div>
    </form>

    <!-- Tabel Data Produk -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4" style="width: 25%;">Produk</th>
                        <th class="py-3 px-4" style="width: 15%;">Kategori</th>
                        <th class="py-3 px-4" style="width: 15%;">Harga</th>
                        <th class="py-3 px-4" style="width: 10%;">Stok</th>
                        <th class="py-3 px-4" style="width: 25%;">Deskripsi</th>
                        <th class="py-3 px-4 text-right" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $product->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $product->category ?? '-' }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $product->stock }}</span>
                                    @if($product->stock <= $product->min_stock)
                                        <span class="px-2 py-0.5 text-xs font-medium bg-red-50 text-red-700 border border-red-200 rounded-full">Alert</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-full">Aman</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-500 max-w-xs truncate">
                                {{ $product->description ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition border border-gray-200 bg-white shadow-sm" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition border border-gray-200 bg-white shadow-sm" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                Belum ada produk. Klik <strong>"+ Tambah produk"</strong> untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection