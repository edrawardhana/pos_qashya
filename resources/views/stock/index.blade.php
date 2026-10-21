@extends('layouts.app')

@section('title', 'Manajemen Stok · Qashya POS')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Manajemen stok</h1>

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Form perubahan stok --}}
    <section class="h-fit rounded bg-white p-6 shadow">
        <h2 class="mb-4 font-semibold">Catat perubahan stok</h2>

        <form method="POST" action="{{ route('stock.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="product_id" class="block text-sm font-medium">Produk</label>
                <select id="product_id" name="product_id" class="mt-1 w-full rounded border px-3 py-2 text-sm">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->name }} (stok {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium">Jenis</label>
                <select id="type" name="type" class="mt-1 w-full rounded border px-3 py-2 text-sm">
                    <option value="masuk" @selected(old('type') === 'masuk')>Masuk (tambah stok)</option>
                    <option value="keluar" @selected(old('type') === 'keluar')>Keluar (kurangi stok)</option>
                    <option value="penyesuaian" @selected(old('type') === 'penyesuaian')>Penyesuaian (atur stok menjadi)</option>
                </select>
            </div>

            <div>
                <label for="qty" class="block text-sm font-medium">Jumlah</label>
                <input id="qty" name="qty" type="number" min="0" value="{{ old('qty') }}"
                    class="mt-1 w-full rounded border px-3 py-2 text-sm">
            </div>

            <div>
                <label for="note" class="block text-sm font-medium">Keterangan (opsional)</label>
                <input id="note" name="note" type="text" maxlength="255" value="{{ old('note') }}"
                    class="mt-1 w-full rounded border px-3 py-2 text-sm">
            </div>

            <button type="submit" class="w-full rounded bg-black py-2 text-sm text-white">Simpan</button>
        </form>
    </section>

    {{-- Riwayat stok --}}
    <section class="lg:col-span-2">
        <form method="GET" action="{{ route('stock.index') }}" class="mb-4 flex flex-wrap gap-3">
            <select name="product_id" class="rounded border px-3 py-2 text-sm">
                <option value="">Semua produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
                @endforeach
            </select>
            <select name="type" class="rounded border px-3 py-2 text-sm">
                <option value="">Semua jenis</option>
                <option value="masuk" @selected(request('type') === 'masuk')>Masuk</option>
                <option value="keluar" @selected(request('type') === 'keluar')>Keluar</option>
                <option value="penyesuaian" @selected(request('type') === 'penyesuaian')>Penyesuaian</option>
            </select>
            <button type="submit" class="rounded border px-4 py-2 text-sm">Terapkan</button>
        </form>

        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2 text-right">Perubahan</th>
                        <th class="px-4 py-2 text-right">Stok</th>
                        <th class="px-4 py-2">Keterangan</th>
                        <th class="px-4 py-2">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $log->product_name }}</td>
                            <td class="px-4 py-2 capitalize">{{ $log->type }}</td>
                            <td class="px-4 py-2 text-right font-mono {{ $log->qty >= 0 ? 'text-green-700' : 'text-red-600' }}">
                                {{ $log->qty > 0 ? '+' : '' }}{{ $log->qty }}
                            </td>
                            <td class="px-4 py-2 text-right">{{ $log->stock_after }}</td>
                            <td class="px-4 py-2">{{ $log->note ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $log->user?->name ?? 'Sistem' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </section>
</div>
@endsection