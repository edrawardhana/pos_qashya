<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sku' => strtoupper(trim((string) $this->input('sku'))),
            'name' => trim((string) $this->input('name')),
        ]);
    }

    public function rules(): array
    {
        $ignoreId = $this->route('product')?->id;

        return [
            'sku' => [
                'required', 'string', 'max:30',
                'regex:/^[A-Za-z0-9-]+$/',
                Rule::unique('products', 'sku')->ignore($ignoreId),
            ],
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1536'], // 1,5 MB
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah dipakai produk lain.',
            'sku.regex' => 'SKU hanya boleh huruf, angka, dan tanda hubung.',
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'price.required' => 'Harga jual wajib diisi.',
            'price.integer' => 'Harga harus berupa bilangan bulat.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'min_stock.required' => 'Stok minimum wajib diisi.',
            'min_stock.integer' => 'Stok minimum harus berupa bilangan bulat.',
            'min_stock.min' => 'Stok minimum tidak boleh negatif.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 1,5 MB.',
        ];
    }
}