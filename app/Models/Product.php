<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'price',
        'cost',
        'stock',
        'min_stock',
        'image_url',
    ];

    public function scopeSearch($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('code', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeCategory($query, ?string $category)
    {
        return $query->when($category, fn ($q) => $q->where('category', $category));
    }
}