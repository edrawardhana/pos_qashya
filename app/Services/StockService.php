<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * Mengurangi stok karena penjualan.
     * Dipanggil dari dalam DB::transaction() di CashierController,
     * dan produk yang diberikan sudah dikunci di sana.
     */
    public function deductForSale(Product $product, int $qty, Transaction $transaction, ?User $by): void
    {
        $product->decrement('stock', $qty);

        StockLog::record(
            product: $product,
            type: StockLog::TYPE_KELUAR,
            qty: -$qty,
            stockAfter: $product->stock,
            note: "Penjualan {$transaction->code}",
            by: $by,
            transactionId: $transaction->id,
        );
    }

    /**
     * Perubahan stok manual oleh admin.
     * - masuk: menambah stok sebesar qty
     * - keluar: mengurangi stok sebesar qty
     * - penyesuaian: mengatur stok menjadi qty
     */
    public function adjust(int $productId, string $type, int $qty, ?string $note, User $by): Product
    {
        return DB::transaction(function () use ($productId, $type, $qty, $note, $by) {
            $product = Product::whereKey($productId)->lockForUpdate()->firstOrFail();

            $newStock = match ($type) {
                StockLog::TYPE_MASUK => $product->stock + $qty,
                StockLog::TYPE_KELUAR => $product->stock - $qty,
                StockLog::TYPE_PENYESUAIAN => $qty,
            };

            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'qty' => "Stok keluar melebihi stok tersedia ({$product->stock}).",
                ]);
            }

            $delta = $newStock - $product->stock;
            $product->update(['stock' => $newStock]);

            StockLog::record(
                product: $product,
                type: $type,
                qty: $delta,
                stockAfter: $newStock,
                note: $note ?: $this->defaultNote($type),
                by: $by,
            );

            return $product;
        });
    }

    private function defaultNote(string $type): string
    {
        return match ($type) {
            StockLog::TYPE_MASUK => 'Stok masuk',
            StockLog::TYPE_KELUAR => 'Stok keluar',
            StockLog::TYPE_PENYESUAIAN => 'Penyesuaian stok',
        };
    }
}