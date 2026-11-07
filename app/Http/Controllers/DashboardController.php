<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\SalesReportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private SalesReportService $reports) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->hasRole(Role::ADMIN);
        $now = now();

        $lowStockQuery = Product::query()->whereColumn('stock', '<=', 'min_stock');

        $data = [
            'isAdmin' => $isAdmin,
            'lowStockCount' => (clone $lowStockQuery)->count(),
            'lowStockProducts' => (clone $lowStockQuery)
                ->orderBy('stock')
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'stock', 'min_stock']),
        ];

        if ($isAdmin) {
            $todayStart = $now->copy()->startOfDay();
            $todayEnd = $now->copy()->endOfDay();
            $summary = $this->reports->summary($todayStart, $todayEnd);

            $daily = $this->reports
                ->daily($now->copy()->subDays(6)->startOfDay(), $todayEnd)
                ->keyBy(fn (array $day) => $day['date']->toDateString());

            $data += [
                'salesToday' => $summary['omzet'],
                'transactionsToday' => $summary['count'],
                'itemsSoldToday' => TransactionItem::query()
                    ->whereHas('transaction', fn (Builder $query) => $query->whereBetween('created_at', [$todayStart, $todayEnd]))
                    ->sum('qty'),
                'salesTrend' => collect(range(6, 0))
                    ->map(function (int $daysAgo) use ($now, $daily): array {
                        $date = $now->copy()->subDays($daysAgo);
                        $day = $daily->get($date->toDateString());

                        return [
                            'label' => $date->locale('id')->translatedFormat('d M'),
                            'total' => $day['omzet'] ?? 0,
                        ];
                    }),
            ];
        } else {
            $data['recentTransactions'] = Transaction::query()
                ->where('cashier_id', $user->id)
                ->withCount('items')
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}
