<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustRequest;
use App\Models\Product;
use App\Models\StockLog;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(private StockService $stock) {}

    public function index(Request $request): View
    {
        $logs = StockLog::query()
            ->with('user')
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('stock.index', [
            'logs' => $logs,
            'products' => Product::orderBy('name')->get(['id', 'name', 'stock']),
        ]);
    }

    public function store(StockAdjustRequest $request): RedirectResponse
    {
        $product = $this->stock->adjust(
            productId: $request->integer('product_id'),
            type: $request->string('type')->toString(),
            qty: $request->integer('qty'),
            note: $request->input('note'),
            by: $request->user(),
        );

        return redirect()
            ->route('stock.index')
            ->with('status', "Stok {$product->name} sekarang {$product->stock}.");
    }
}