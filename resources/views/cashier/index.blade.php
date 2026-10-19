@extends('layouts.app')

@section('title', 'Transaksi · Qashya POS')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Transaksi</h1>

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Daftar produk --}}
    <section class="lg:col-span-2">
        <input id="search" type="text" placeholder="Cari nama atau SKU"
            class="mb-4 w-full rounded border px-3 py-2 text-sm">

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($products as $product)
                <button type="button"
                        data-search="{{ strtolower($product->name.' '.$product->sku) }}"
                        onclick="addToCart({{ $product->id }})"
                        @disabled($product->stock === 0)
                        class="rounded bg-white p-4 text-left shadow hover:ring-2 hover:ring-black disabled:opacity-50 disabled:hover:ring-0 product-card">
                    <p class="font-mono text-xs text-gray-500">{{ $product->sku }}</p>
                    <p class="font-medium">{{ $product->name }}</p>
                    <p class="mt-1 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs {{ $product->stock === 0 ? 'text-red-600' : 'text-gray-500' }}">
                        {{ $product->stock === 0 ? 'Habis' : 'Stok: '.$product->stock }}
                    </p>
                </button>
            @endforeach
        </div>
    </section>

    {{-- Keranjang & checkout --}}
    <aside class="rounded bg-white p-6 shadow lg:sticky lg:top-6 lg:self-start">
        <h2 class="mb-3 text-lg font-semibold">Keranjang</h2>

        <ul id="cart-items" class="divide-y"></ul>
        <p id="cart-empty" class="py-6 text-center text-sm text-gray-500">Keranjang masih kosong.</p>

        <div class="mt-4 flex justify-between border-t pt-4 font-semibold">
            <span>Total</span>
            <span id="cart-total">Rp 0</span>
        </div>

        <form id="checkout-form" method="POST" action="{{ route('cashier.store') }}" class="mt-4 space-y-4">
            @csrf
            <div id="cart-inputs"></div>

            <div>
                <label for="customer_name" class="block text-sm font-medium">Nama pelanggan (opsional)</label>
                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}"
                    class="mt-1 w-full rounded border px-3 py-2 text-sm">
            </div>

            <div>
                <p class="text-sm font-medium">Metode pembayaran</p>
                <div class="mt-1 flex gap-4 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="method" value="cash" checked> Tunai
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="method" value="transfer"> Transfer
                    </label>
                </div>
            </div>

            <div id="paid-wrapper">
                <label for="paid" class="block text-sm font-medium">Jumlah bayar (Rp)</label>
                <input id="paid" name="paid" type="number" min="0" value="{{ old('paid') }}"
                    class="mt-1 w-full rounded border px-3 py-2 text-sm">
                <p id="change-text" class="mt-2 text-sm"></p>
            </div>

            <button id="submit-btn" type="submit" disabled
                    class="w-full rounded bg-black py-2 text-sm text-white disabled:opacity-40">
                Simpan transaksi
            </button>
        </form>
    </aside>
</div>

<script>
    const products = @json($products);
    const byId = Object.fromEntries(products.map((p) => [String(p.id), p]));
    const cart = {}; // { productId: qty }

    const fmt = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

    function addToCart(id) {
        const key = String(id);
        const product = byId[key];
        const current = cart[key] ?? 0;
        if (current + 1 > product.stock) {
            alert('Stok ' + product.name + ' tidak cukup.');
            return;
        }
        cart[key] = current + 1;
        render();
    }

    function changeQty(id, delta) {
        const key = String(id);
        const next = (cart[key] ?? 0) + delta;
        if (next <= 0) {
            delete cart[key];
        } else if (next > byId[key].stock) {
            alert('Stok ' + byId[key].name + ' tidak cukup.');
            return;
        } else {
            cart[key] = next;
        }
        render();
    }

    function button(label, onClick) {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = label;
        b.className = 'rounded border px-2 py-0.5 text-xs hover:bg-gray-100';
        b.addEventListener('click', onClick);
        return b;
    }

    function render() {
        const list = document.getElementById('cart-items');
        const inputs = document.getElementById('cart-inputs');
        list.replaceChildren();
        inputs.replaceChildren();

        let total = 0;
        let i = 0;

        for (const [id, qty] of Object.entries(cart)) {
            const p = byId[id];
            total += p.price * qty;

            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-2 py-2 text-sm';

            const info = document.createElement('div');
            const name = document.createElement('p');
            name.className = 'font-medium';
            name.textContent = p.name;
            const detail = document.createElement('p');
            detail.className = 'text-xs text-gray-500';
            detail.textContent = fmt(p.price) + ' × ' + qty + ' = ' + fmt(p.price * qty);
            info.append(name, detail);

            const controls = document.createElement('div');
            controls.className = 'flex items-center gap-1';
            controls.append(
                button('−', () => changeQty(id, -1)),
                document.createTextNode(' ' + qty + ' '),
                button('+', () => changeQty(id, 1)),
                button('Hapus', () => changeQty(id, -qty))
            );

            li.append(info, controls);
            list.append(li);

            for (const [name, value] of [['product_id', id], ['qty', qty]]) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${i}][${name}]`;
                input.value = value;
                inputs.append(input);
            }
            i++;
        }

        const hasItems = i > 0;
        document.getElementById('cart-empty').classList.toggle('hidden', hasItems);
        document.getElementById('cart-total').textContent = fmt(total);
        document.getElementById('submit-btn').disabled = !hasItems;
        updatePayment(total);
    }

    function currentTotal() {
        return Object.entries(cart).reduce((sum, [id, qty]) => sum + byId[id].price * qty, 0);
    }

    function updatePayment(total = currentTotal()) {
        const method = document.querySelector('input[name="method"]:checked').value;
        const paidWrapper = document.getElementById('paid-wrapper');
        const paidInput = document.getElementById('paid');
        const changeText = document.getElementById('change-text');

        paidWrapper.classList.toggle('hidden', method === 'transfer');
        paidInput.disabled = method === 'transfer';

        if (method === 'transfer') {
            changeText.textContent = '';
            return;
        }

        const paid = Number(paidInput.value || 0);
        if (paid === 0) {
            changeText.textContent = '';
        } else if (paid < total) {
            changeText.className = 'mt-2 text-sm text-red-600';
            changeText.textContent = 'Kurang ' + fmt(total - paid);
        } else {
            changeText.className = 'mt-2 text-sm text-green-700';
            changeText.textContent = 'Kembalian ' + fmt(paid - total);
        }
    }

    // Filter produk di sisi browser
    document.getElementById('search').addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        document.querySelectorAll('.product-card').forEach((el) => {
            el.classList.toggle('hidden', q !== '' && !el.dataset.search.includes(q));
        });
    });

    document.querySelectorAll('input[name="method"]').forEach((r) =>
        r.addEventListener('change', () => updatePayment())
    );
    document.getElementById('paid').addEventListener('input', () => updatePayment());

    render();
</script>
@endsection