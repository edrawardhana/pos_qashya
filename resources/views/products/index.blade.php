@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Produk</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah produk</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                placeholder="Cari nama, kode, atau deskripsi produk">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Semua kategori</option>
                @foreach ($categoryList as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary">Cari</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-end">Cost (Modal)</th>
                    <th class="text-end">Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td><code>{{ $product->code }}</code></td>
                        <td>
                            {{ $product->name }}
                            @if($product->image_url)
                                <a href="{{ $product->image_url }}" target="_blank" class="ms-1 text-decoration-none" title="Lihat Foto">
                                    <i class="bi bi-image"></i> 🖼️
                                </a>
                            @endif
                        </td>
                        <td>{{ $product->category ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="text-end">
                            @if($product->cost)
                                Rp {{ number_format($product->cost, 2, ',', '.') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            {{ $product->stock }}
                            @if($product->stock <= $product->min_stock)
                                <span class="badge bg-danger" title="Stok menipis! Min: {{ $product->min_stock }}">Alert</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Ubah</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus produk {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada produk. Klik "Tambah produk" untuk mulai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection