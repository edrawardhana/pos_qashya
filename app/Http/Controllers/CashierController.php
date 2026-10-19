<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        return view('cashier.index', [
            'products' => Product::orderBy('name')->get(['id', 'sku', 'name', 'category', 'price', 'stock']),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        // Gabungkan baris produk yang sama, lalu jumlahkan qty-nya.
        $lines = collect($request->validated('items'))
            ->groupBy('product_id')
            ->map(fn ($rows) => (int) $rows->sum('qty'));

        $method = $request->string('method')->toString();

        try {
            $transaction = DB::transaction(function () use ($request, $lines, $method) {
                $products = Product::whereIn('id', $lines->keys())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $total = 0;
                $itemRows = [];

                foreach ($lines as $productId => $qty) {
                    $product = $products->get($productId);

                    if (! $product) {
                        throw ValidationException::withMessages(['items' => 'Produk tidak ditemukan.']);
                    }

                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Stok {$product->name} tidak cukup (tersisa {$product->stock}).",
                        ]);
                    }

                    $subtotal = $product->price * $qty;
                    $total += $subtotal;

                    $itemRows[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'category' => $product->category,
                        'price' => $product->price,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }

                // Harga dihitung ulang dari database, bukan dari browser.
                $isTransfer = $method === Transaction::METHOD_TRANSFER;
                $paid = $isTransfer ? $total : (int) $request->integer('paid');

                if ($paid < $total) {
                    throw ValidationException::withMessages([
                        'paid' => 'Jumlah bayar kurang dari total belanja.',
                    ]);
                }

                $transaction = Transaction::create([
                    'code' => Transaction::generateCode(),
                    'cashier_id' => $request->user()->id,
                    'customer_name' => $request->input('customer_name'),
                    'total' => $total,
                    'paid' => $paid,
                    'change' => $paid - $total,
                    'payment_method' => $method,
                ]);

                $transaction->items()->createMany($itemRows);

                foreach ($lines as $productId => $qty) {
                    $products->get($productId)->decrement('stock', $qty);
                }

                return $transaction;
            });
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }

        return redirect()
            ->route('cashier.show', $transaction)
            ->with('status', 'Transaksi '.$transaction->code.' berhasil disimpan.');
    }

    public function show(Transaction $transaction): View
    {
        abort_unless($transaction->cashier_id === auth()->id(), 403);

        return view('cashier.receipt', [
            'transaction' => $transaction->load('items', 'cashier'),
        ]);
    }
}