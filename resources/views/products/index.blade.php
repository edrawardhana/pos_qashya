@extends('layouts.app')

@section('title', 'Produk · Qashya POS')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Produk</h1>
    @if (auth()->user()->hasRole('admin'))
        <a href="{{ route('products.create') }}" class="rounded bg-black px-4 py-2 text-sm text-white">+ Tambah produk</a>
    @endif
</div>

<form method="GET" action="{{ route('products.index') }}" class="mb-4 grid gap-3 sm:grid-cols-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, SKU, atau deskripsi"
        class="rounded border px-3 py-2 text-sm sm:col-span-2">
    <select name="category" class="rounded border px-3 py-2 text-sm">
        <option value="">Semua kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
        @endforeach
    </select>
    <select name="stock" class="rounded border px-3 py-2 text-sm">
        <option value="">Semua status stok</option>
        <option value="aman" @selected(request('stock') === 'aman')>Aman</option>
        <option value="menipis" @selected(request('stock') === 'menipis')>Menipis</option>
        <option value="habis" @selected(request('stock') === 'habis')>Habis</option>
    </select>
    <button type="submit" class="rounded border px-4 py-2 text-sm sm:col-span-4 sm:justify-self-start">Terapkan</button>
</form>

<div class="overflow-x-auto rounded bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Kategori</th>
                <th class="px-4 py-2 text-right">Harga</th>
                <th class="px-4 py-2 text-right">Stok</th>
                <th class="px-4 py-2">Status</th>
                @if (auth()->user()->hasRole('admin'))
                    <th class="px-4 py-2 text-right">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($products as $product)
                <tr>
                    <td class="flex items-center gap-3 px-4 py-2">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="" class="h-10 w-10 rounded object-cover">
                        @else
                            <div class="h-10 w-10 rounded bg-gray-200"></div>
                        @endif
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="font-mono text-xs text-gray-500">{{ $product->sku }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-2">{{ $product->category }}</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right">{{ $product->stock }}</td>
                    <td class="px-4 py-2">
                        @php
                            $labels = ['aman' => 'Aman', 'menipis' => 'Menipis', 'habis' => 'Habis'];
                            $colors = ['aman' => 'text-green-700', 'menipis' => 'text-amber-600', 'habis' => 'text-red-600'];
                        @endphp
                        <span class="{{ $colors[$product->stock_status] }}">{{ $labels[$product->stock_status] }}</span>
                    </td>
                    @if (auth()->user()->hasRole('admin'))
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Ubah</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
                                onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada produk yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection