@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    <h4 class="mb-3">Ubah Produk</h4>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Kode Produk (SKU)</label>
            <input type="text" id="code" name="code" value="{{ old('code', $product->code) }}"
                class="form-control @error('code') is-invalid @enderror" required>
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}"
                class="form-control @error('category') is-invalid @enderror" placeholder="Contoh: shirt, pants, accessories" required>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Produk</label>
            <textarea id="description" name="description" rows="3"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="price" class="form-label">Harga Jual (Rp)</label>
                <input type="number" step="0.01" id="price" name="price" min="0" value="{{ old('price', $product->price) }}"
                    class="form-control @error('price') is-invalid @enderror" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="cost" class="form-label">Harga Pokok / Cost (Rp)</label>
                <input type="number" step="0.01" id="cost" name="cost" min="0" value="{{ old('cost', $product->cost) }}"
                    class="form-control @error('cost') is-invalid @enderror">
                @error('cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $product->stock) }}"
                    class="form-control @error('stock') is-invalid @enderror" required>
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="min_stock" class="form-label">Stok Minimum (Alert)</label>
                <input type="number" id="min_stock" name="min_stock" min="0" value="{{ old('min_stock', $product->min_stock) }}"
                    class="form-control @error('min_stock') is-invalid @enderror" required>
                @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="image_url" class="form-label">URL Foto Produk</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                class="form-control @error('image_url') is-invalid @enderror" placeholder="https://example.com/image.jpg">
            @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection