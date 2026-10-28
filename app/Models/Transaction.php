<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    // --- Tambahan Scope ---

    public function scopeSearch(Builder $query, string $term): Builder
    {
        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('code', 'like', "%{$term}%")
                ->orWhere('customer_name', 'like', "%{$term}%")
                ->orWhereHas('cashier', fn (Builder $c) => $c->where('name', 'like', "%{$term}%"));
        });
    }

    public function scopeCreatedBetween(Builder $query, ?Carbon $from, ?Carbon $to): Builder
    {
        return $query
            ->when($from, fn (Builder $q) => $q->where('created_at', '>=', $from))
            ->when($to, fn (Builder $q) => $q->where('created_at', '<=', $to));
    }

    public function scopePaymentMethod(Builder $query, ?string $method): Builder
    {
        return $query->when($method, fn (Builder $q) => $q->where('payment_method', $method));
    }
}