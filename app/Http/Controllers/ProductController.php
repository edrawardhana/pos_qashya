<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->search($request->string('q')->toString())
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->when($request->filled('stock'), fn ($q) => $q->stockStatus($request->string('stock')->toString()))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('products.create', ['categories' => Product::CATEGORIES]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create($data);

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Product::CATEGORIES,
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']); // pertahankan gambar lama
        }

        $product->update($data);

        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}