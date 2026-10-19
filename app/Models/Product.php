<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const CATEGORIES = ['Gamis', 'Hijab', 'Tunik', 'Outer', 'Mukena', 'Aksesoris'];

    public const STOCK_HABIS = 'habis';
    public const STOCK_MENIPIS = 'menipis';
    public const STOCK_AMAN = 'aman';

    protected $fillable = [
        'sku', 'name', 'category', 'price', 'stock', 'min_stock', 'description', 'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeStockStatus(Builder $query, string $status): Builder
    {
        return match ($status) {
            self::STOCK_HABIS => $query->where('stock', 0),
            self::STOCK_MENIPIS => $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock'),
            self::STOCK_AMAN => $query->whereColumn('stock', '>', 'min_stock'),
            default => $query,
        };
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0) {
            return self::STOCK_HABIS;
        }

        return $this->stock <= $this->min_stock ? self::STOCK_MENIPIS : self::STOCK_AMAN;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}