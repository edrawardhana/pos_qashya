@extends('layouts.app')

@section('title', 'Struk '.$transaction->code.' · Qashya POS')

@section('content')
<div class="mx-auto max-w-md rounded bg-white p-6 shadow">
    <h1 class="text-center text-xl font-semibold">Qashya Collection</h1>
    <p class="mb-4 text-center font-mono text-xs text-gray-500">{{ $transaction->code }}</p>

    <dl class="mb-4 space-y-1 text-sm">
        <div class="flex justify-between"><dt>Tanggal</dt><dd>{{ $transaction->created_at->format('d/m/Y H:i') }}</dd></div>
        <div class="flex justify-between"><dt>Kasir</dt><dd>{{ $transaction->cashier->name }}</dd></div>
        <div class="flex justify-between"><dt>Pelanggan</dt><dd>{{ $transaction->customer_name ?? '-' }}</dd></div>
    </dl>

    <table class="mb-4 w-full text-sm">
        <tbody class="divide-y">
            @foreach ($transaction->items as $item)
                <tr>
                    <td class="py-2">
                        {{ $item->product_name }}<br>
                        <span class="text-xs text-gray-500">{{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <dl class="space-y-1 border-t pt-3 text-sm">
        <div class="flex justify-between font-semibold"><dt>Total</dt><dd>Rp {{ number_format($transaction->total, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between"><dt>Metode</dt><dd>{{ $transaction->payment_method === 'cash' ? 'Tunai' : 'Transfer' }}</dd></div>
        <div class="flex justify-between"><dt>Dibayar</dt><dd>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between"><dt>Kembalian</dt><dd>Rp {{ number_format($transaction->change, 0, ',', '.') }}</dd></div>
    </dl>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('cashier.index') }}" class="flex-1 rounded bg-black py-2 text-center text-sm text-white">Transaksi baru</a>
        <button type="button" onclick="window.print()" class="flex-1 rounded border py-2 text-sm">Cetak</button>
    </div>
</div>
@endsection