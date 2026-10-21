<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    public const TYPE_MASUK = 'masuk';
    public const TYPE_KELUAR = 'keluar';
    public const TYPE_PENYESUAIAN = 'penyesuaian';

    public const TYPES = [self::TYPE_MASUK, self::TYPE_KELUAR, self::TYPE_PENYESUAIAN];

    protected $fillable = [
        'product_id', 'product_name', 'type', 'qty', 'stock_after', 'note', 'user_id', 'transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'stock_after' => 'integer',
        ];
    }

    public static function record(
        Product $product,
        string $type,
        int $qty,
        int $stockAfter,
        ?string $note = null,
        ?User $by = null,
        ?int $transactionId = null,
    ): self {
        return self::create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'type' => $type,
            'qty' => $qty,
            'stock_after' => $stockAfter,
            'note' => $note,
            'user_id' => $by?->id,
            'transaction_id' => $transactionId,
        ]);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}