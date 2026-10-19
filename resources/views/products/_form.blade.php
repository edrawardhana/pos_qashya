@php
    $isEdit = isset($product);
@endphp

<form method="POST"
        action="{{ $isEdit ? route('products.update', $product) : route('products.store') }}"
        enctype="multipart/form-data"
    class="grid max-w-2xl gap-4 rounded bg-white p-6 shadow">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium">Nama produk</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}"
            placeholder="mis. Gamis Syar'i Aisyah" class="mt-1 w-full rounded border px-3 py-2">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="sku" class="block text-sm font-medium">SKU / Kode</label>
            <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku ?? '') }}"
                placeholder="GMS-001" class="mt-1 w-full rounded border px-3 py-2 font-mono uppercase">
            @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="category" class="block text-sm font-medium">Kategori</label>
            <select id="category" name="category" class="mt-1 w-full rounded border px-3 py-2">
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
            @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label for="price" class="block text-sm font-medium">Harga jual (Rp)</label>
            <input id="price" name="price" type="number" min="0" value="{{ old('price', $product->price ?? '') }}"
                class="mt-1 w-full rounded border px-3 py-2">
            @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium">Stok</label>
            <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? '') }}"
                class="mt-1 w-full rounded border px-3 py-2">
            @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="min_stock" class="block text-sm font-medium">Stok minimum</label>
            <input id="min_stock" name="min_stock" type="number" min="0"
                value="{{ old('min_stock', $product->min_stock ?? 5) }}"
                class="mt-1 w-full rounded border px-3 py-2">
            @error('min_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium">Deskripsi</label>
        <textarea id="description" name="description" rows="3"
                class="mt-1 w-full rounded border px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-medium">Gambar produk</label>
        @if ($isEdit && $product->image_url)
            <img src="{{ $product->image_url }}" alt="" class="mb-2 h-24 w-24 rounded object-cover">
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png" class="mt-1 text-sm">
        <p class="mt-1 text-xs text-gray-500">JPG/PNG, maks 1,5 MB.{{ $isEdit ? ' Kosongkan jika tidak ingin mengganti gambar.' : '' }}</p>
        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-3">
        <button type="submit" class="rounded bg-black px-4 py-2 text-sm text-white">Simpan</button>
        <a href="{{ route('products.index') }}" class="rounded border px-4 py-2 text-sm">Batal</a>
    </div>
</form>