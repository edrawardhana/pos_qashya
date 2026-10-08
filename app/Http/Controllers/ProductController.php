<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
{
    // Ambil daftar kategori unik untuk dropdown filter
    $categoryList = Product::select('category')->distinct()->pluck('category');

    $query = Product::query();

    // Filter pencarian teks (nama, SKU, atau deskripsi)
    if ($request->filled('q')) {
        $keyword = $request->q;
        $query->where(function($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
            ->orWhere('code', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    // Filter berdasarkan kategori
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Filter berdasarkan status stok
    if ($request->filled('stock_status')) {
        if ($request->stock_status === 'safe') {
            $query->whereColumn('stock', '>', 'min_stock');
        } elseif ($request->stock_status === 'alert') {
            $query->whereColumn('stock', '<=', 'min_stock');
        }
    }

    $products = $query->latest()->paginate(10);

    return view('admin.products.index', compact('products', 'categoryList'));
}

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Product::create($data);

        return redirect()->route('admin.products.index') // Pastikan nama route sesuai (atau sesuaikan dengan penamaan route produk Anda)
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules($product->id));

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'code'        => ['required', 'string', 'max:50', Rule::unique('products', 'code')->ignore($ignoreId)],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category'    => ['required', 'string', 'max:100'],
            'price'       => ['required', 'numeric', 'min:0'],
            'cost'        => ['nullable', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'image_url'   => ['nullable', 'url', 'max:255'],
        ];
    }
}