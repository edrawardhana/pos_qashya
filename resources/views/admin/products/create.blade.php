@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">Tambah Produk Baru</h4>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="code" class="form-label fw-semibold">Kode Produk (SKU)</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: SKU-001" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Kemeja Flanel" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Kategori</label>
                            <input type="text" id="category" name="category" value="{{ old('category') }}"
                                class="form-control @error('category') is-invalid @enderror" placeholder="Contoh: shirt, pants, accessories" required>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label fw-semibold">URL Foto Produk</label>
                            <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}"
                                class="form-control @error('image_url') is-invalid @enderror" placeholder="https://example.com/image.jpg">
                            @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-semibold">Harga Jual (Rp)</label>
                                <input type="number" step="0.01" id="price" name="price" min="0" value="{{ old('price') }}"
                                    class="form-control @error('price') is-invalid @enderror" placeholder="0" required>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cost" class="form-label fw-semibold">Harga Pokok (Rp)</label>
                                <input type="number" step="0.01" id="cost" name="cost" min="0" value="{{ old('cost') }}"
                                    class="form-control @error('cost') is-invalid @enderror" placeholder="0">
                                @error('cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label fw-semibold">Stok Awal</label>
                                <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', 0) }}"
                                    class="form-control @error('stock') is-invalid @enderror" required>
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="min_stock" class="form-label fw-semibold">Stok Minimum</label>
                                <input type="number" id="min_stock" name="min_stock" min="0" value="{{ old('min_stock', 5) }}"
                                    class="form-control @error('min_stock') is-invalid @enderror" required>
                                @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi Produk</label>
                            <textarea id="description" name="description" rows="4"
                                class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan deskripsi produk di sini...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection