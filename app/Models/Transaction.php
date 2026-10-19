<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    public const METHOD_CASH = 'cash';
    public const METHOD_TRANSFER = 'transfer';

    protected $fillable = [
        'code', 'cashier_id', 'customer_name', 'total', 'paid', 'change', 'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
            'paid' => 'integer',
            'change' => 'integer',
        ];
    }

    public static function generateCode(): string
    {
        return 'TRX-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}