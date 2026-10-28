<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionHistoryController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'method' => ['nullable', Rule::in([Transaction::METHOD_CASH, Transaction::METHOD_TRANSFER])],
            'cashier_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $from = filled($validated['from'] ?? null)
            ? Carbon::createFromFormat('Y-m-d', $validated['from'])->startOfDay()
            : null;
        $to = filled($validated['to'] ?? null)
            ? Carbon::createFromFormat('Y-m-d', $validated['to'])->endOfDay()
            : null;

        if ($from && $to && $to->lt($from)) {
            return back()->withInput()->withErrors(['to' => 'Tanggal akhir tidak boleh sebelum tanggal awal.']);
        }

        // Kasir hanya melihat transaksinya sendiri. Admin bisa memfilter per kasir.
        $cashierId = $user->hasRole('kasir')
            ? $user->id
            : ($validated['cashier_id'] ?? null);

        $base = Transaction::query()
            ->search($validated['q'] ?? '')
            ->createdBetween($from, $to)
            ->paymentMethod($validated['method'] ?? null)
            ->when($cashierId, fn ($q) => $q->where('cashier_id', $cashierId));

        // Ringkasan dihitung dari query yang sama, sebelum paginasi.
        $totals = (clone $base)
            ->selectRaw('COUNT(*) as trx_count, COALESCE(SUM(total), 0) as omzet')
            ->first();

        $transactions = $base
            ->with('cashier')
            ->withCount('items')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('history.index', [
            'transactions' => $transactions,
            'totals' => [
                'count' => (int) $totals->trx_count,
                'omzet' => (int) $totals->omzet,
            ],
            'cashiers' => $user->hasRole('admin')
                ? User::orderBy('name')->get(['id', 'name'])
                : collect(),
            'filters' => $validated,
        ]);
    }

    public function show(Request $request, Transaction $transaction): View
    {
        $user = $request->user();

        abort_unless(
            $user->hasRole('admin') || $transaction->cashier_id === $user->id,
            403
        );

        return view('cashier.receipt', [
            'transaction' => $transaction->load('items', 'cashier'),
        ]);
    }
}