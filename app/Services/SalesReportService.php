<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class SalesReportService
{
    /**
     * Ringkasan omzet, jumlah transaksi, dan rata-rata per transaksi.
     */
    public function summary(Carbon $from, Carbon $to): array
    {
        $row = Transaction::query()
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) as trx_count, COALESCE(SUM(total), 0) as omzet')
            ->first();

        $count = (int) $row->trx_count;
        $omzet = (int) $row->omzet;

        return [
            'omzet' => $omzet,
            'count' => $count,
            'average' => $count > 0 ? (int) round($omzet / $count) : 0,
        ];
    }

    /**
     * Tren harian. Hanya hari yang punya transaksi yang dikembalikan.
     * Pengelompokan dilakukan di PHP agar tanggal mengikuti zona waktu aplikasi.
     */
    public function daily(Carbon $from, Carbon $to): Collection
    {
        $transactions = Transaction::query()
            ->whereBetween('created_at', [$from, $to])
            ->get(['created_at', 'total']);

        $days = [];

        foreach ($transactions as $trx) {
            $key = $trx->created_at->toDateString();

            $days[$key] ??= ['date' => $trx->created_at->copy()->startOfDay(), 'count' => 0, 'omzet' => 0];
            $days[$key]['count']++;
            $days[$key]['omzet'] += $trx->total;
        }

        return collect($days)->sortKeys()->values();
    }

    /**
     * Produk terlaris berdasarkan jumlah qty terjual.
     */
    public function topProducts(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        return TransactionItem::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->groupBy('transaction_items.product_name', 'transaction_items.category')
            ->selectRaw(
                'transaction_items.product_name, transaction_items.category, '.
                'SUM(transaction_items.qty) as qty, SUM(transaction_items.subtotal) as revenue'
            )
            ->orderByDesc('qty')
            ->limit($limit)
            ->get();
    }

    /**
     * Rincian omzet per metode pembayaran.
     */
    public function byPaymentMethod(Carbon $from, Carbon $to): Collection
    {
        return Transaction::query()
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as trx_count, SUM(total) as omzet')
            ->get();
    }

    /**
     * Transaksi beserta item dan kasir untuk diekspor, dibaca per potongan agar hemat memori.
     */
    public function exportCursor(Carbon $from, Carbon $to): EloquentCollection|\Illuminate\Support\LazyCollection
    {
        return Transaction::query()
            ->with(['items', 'cashier'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->lazy(200);
    }
}