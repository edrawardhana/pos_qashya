@extends('layouts.app')

@section('title', 'Riwayat Transaksi · Qashya POS')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Riwayat transaksi</h1>

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="GET" action="{{ route('history.index') }}" class="mb-6 grid gap-3 rounded bg-white p-4 shadow sm:grid-cols-2 lg:grid-cols-6">
    <div class="lg:col-span-2">
        <label for="q" class="block text-xs font-medium text-gray-600">Cari kode, pelanggan, atau kasir</label>
        <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="mis. TRX-261026"
            class="mt-1 w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label for="from" class="block text-xs font-medium text-gray-600">Dari tanggal</label>
        <input id="from" type="date" name="from" value="{{ $filters['from'] ?? '' }}"
            class="mt-1 w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label for="to" class="block text-xs font-medium text-gray-600">Sampai tanggal</label>
        <input id="to" type="date" name="to" value="{{ $filters['to'] ?? '' }}"
            class="mt-1 w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label for="method" class="block text-xs font-medium text-gray-600">Metode</label>
        <select id="method" name="method" class="mt-1 w-full rounded border px-3 py-2 text-sm">
            <option value="">Semua</option>
            <option value="cash" @selected(($filters['method'] ?? '') === 'cash')>Tunai</option>
            <option value="transfer" @selected(($filters['method'] ?? '') === 'transfer')>Transfer</option>
        </select>
    </div>

    @if ($cashiers->isNotEmpty())
        <div>
            <label for="cashier_id" class="block text-xs font-medium text-gray-600">Kasir</label>
            <select id="cashier_id" name="cashier_id" class="mt-1 w-full rounded border px-3 py-2 text-sm">
                <option value="">Semua kasir</option>
                @foreach ($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" @selected((string) ($filters['cashier_id'] ?? '') === (string) $cashier->id)>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="flex items-end gap-3 sm:col-span-2 lg:col-span-6">
        <button type="submit" class="rounded bg-black px-4 py-2 text-sm text-white">Terapkan</button>
        <a href="{{ route('history.index') }}" class="rounded border px-4 py-2 text-sm">Reset</a>
    </div>
</form>

<div class="mb-4 grid gap-4 sm:grid-cols-2">
    <div class="rounded bg-white p-4 shadow">
        <p class="text-sm text-gray-500">Jumlah transaksi</p>
        <p class="text-xl font-semibold">{{ $totals['count'] }}</p>
    </div>
    <div class="rounded bg-white p-4 shadow">
        <p class="text-sm text-gray-500">Total omzet hasil filter</p>
        <p class="text-xl font-semibold">Rp {{ number_format($totals['omzet'], 0, ',', '.') }}</p>
    </div>
</div>

<div class="overflow-x-auto rounded bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Kode</th>
                <th class="px-4 py-2">Waktu</th>
                <th class="px-4 py-2">Kasir</th>
                <th class="px-4 py-2">Pelanggan</th>
                <th class="px-4 py-2 text-right">Item</th>
                <th class="px-4 py-2">Metode</th>
                <th class="px-4 py-2 text-right">Total</th>
                <th class="px-4 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($transactions as $trx)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs">{{ $trx->code }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $trx->cashier->name }}</td>
                    <td class="px-4 py-2">{{ $trx->customer_name ?? '-' }}</td>
                    <td class="px-4 py-2 text-right">{{ $trx->items_count }}</td>
                    <td class="px-4 py-2">{{ $trx->payment_method === 'cash' ? 'Tunai' : 'Transfer' }}</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('history.show', $trx) }}" class="text-blue-600 hover:underline">Lihat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection