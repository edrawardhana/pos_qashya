@extends('layouts.app')

@section('title', 'Laporan Penjualan · Qashya POS')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-2xl font-semibold">Laporan penjualan</h1>
    <a href="{{ route('reports.export', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}"
    class="rounded border px-4 py-2 text-sm hover:bg-gray-100">Unduh CSV</a>
</div>

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="GET" action="{{ route('reports.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
    <div>
        <label for="from" class="block text-sm font-medium">Dari</label>
        <input id="from" type="date" name="from" value="{{ $from->toDateString() }}"
            class="mt-1 rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label for="to" class="block text-sm font-medium">Sampai</label>
        <input id="to" type="date" name="to" value="{{ $to->toDateString() }}"
            class="mt-1 rounded border px-3 py-2 text-sm">
    </div>
    <button type="submit" class="rounded bg-black px-4 py-2 text-sm text-white">Terapkan</button>
</form>

{{-- Ringkasan --}}
<div class="mb-8 grid gap-4 sm:grid-cols-3">
    <div class="rounded bg-white p-5 shadow">
        <p class="text-sm text-gray-500">Total omzet</p>
        <p class="mt-1 text-2xl font-semibold">Rp {{ number_format($summary['omzet'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded bg-white p-5 shadow">
        <p class="text-sm text-gray-500">Jumlah transaksi</p>
        <p class="mt-1 text-2xl font-semibold">{{ $summary['count'] }}</p>
    </div>
    <div class="rounded bg-white p-5 shadow">
        <p class="text-sm text-gray-500">Rata-rata per transaksi</p>
        <p class="mt-1 text-2xl font-semibold">Rp {{ number_format($summary['average'], 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Tren harian --}}
    <section class="lg:col-span-2">
        <h2 class="mb-3 font-semibold">Penjualan per hari</h2>
        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2 text-right">Transaksi</th>
                        <th class="px-4 py-2 text-right">Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($daily as $day)
                        <tr>
                            <td class="px-4 py-2">{{ $day['date']->translatedFormat('l, d M Y') }}</td>
                            <td class="px-4 py-2 text-right">{{ $day['count'] }}</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($day['omzet'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi pada rentang ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="space-y-6">
        {{-- Metode pembayaran --}}
        <section>
            <h2 class="mb-3 font-semibold">Metode pembayaran</h2>
            <div class="rounded bg-white shadow">
                <ul class="divide-y text-sm">
                    @forelse ($payments as $payment)
                        <li class="flex justify-between px-4 py-3">
                            <span>{{ $payment->payment_method === 'cash' ? 'Tunai' : 'Transfer' }}
                                <span class="text-gray-500">({{ $payment->trx_count }} trx)</span>
                            </span>
                            <span>Rp {{ number_format($payment->omzet, 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-gray-500">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        {{-- Produk terlaris --}}
        <section>
            <h2 class="mb-3 font-semibold">Produk terlaris</h2>
            <div class="overflow-x-auto rounded bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2">Produk</th>
                            <th class="px-4 py-2 text-right">Qty</th>
                            <th class="px-4 py-2 text-right">Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($topProducts as $product)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ $product->product_name }}
                                    <p class="text-xs text-gray-500">{{ $product->category }}</p>
                                </td>
                                <td class="px-4 py-2 text-right">{{ $product->qty }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection