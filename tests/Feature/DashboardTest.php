<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_sales_summary_seven_day_trend_and_low_stock_alerts(): void
    {
        $this->travelTo(now()->setDate(2026, 11, 7)->setTime(12, 0));
        $admin = $this->userWithRole(Role::ADMIN);

        $this->product('LOW-001', 'Produk Menipis', 2, 5);
        $this->product('OUT-001', 'Produk Habis', 0, 3);
        $this->product('SAFE-001', 'Produk Aman', 8, 5);

        $transaction = $this->transaction($admin, 'TRX-ADMIN-TODAY', 1500);
        $transaction->items()->create([
            'product_name' => 'Produk Menipis',
            'category' => 'Gamis',
            'price' => 500,
            'qty' => 3,
            'subtotal' => 1500,
        ]);
        $this->transaction($admin, 'TRX-ADMIN-YESTERDAY', 2000, now()->subDay());

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Rp 1.500')
            ->assertSee('Tren penjualan 7 hari')
            ->assertSee('Produk Menipis')
            ->assertSee('Produk Habis')
            ->assertDontSee('Produk Aman')
            ->assertDontSee('TRX-ADMIN-TODAY')
            ->assertDontSee('TRX-ADMIN-YESTERDAY')
            ->assertSee('<strong>1</strong>', false)
            ->assertSee('<strong>3</strong>', false);
    }

    public function test_cashier_sees_only_own_recent_transactions_stock_alerts_and_checkout_link(): void
    {
        $cashier = $this->userWithRole(Role::KASIR);
        $anotherCashier = $this->userWithRole(Role::KASIR);

        $this->product('LOW-002', 'Stok Perlu Restok', 1, 4);
        $this->transaction($cashier, 'TRX-CASHIER-OWN', 1250);
        $this->transaction($anotherCashier, 'TRX-CASHIER-OTHER', 9999);

        $this->actingAs($cashier)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('TRX-CASHIER-OWN')
            ->assertDontSee('TRX-CASHIER-OTHER')
            ->assertSee('Stok Perlu Restok')
            ->assertSee('Mulai Transaksi')
            ->assertSee(route('cashier.index'))
            ->assertDontSee('Penjualan hari ini')
            ->assertDontSee('Tren penjualan 7 hari');
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(
            ['name' => $roleName],
            ['label' => ucfirst($roleName)]
        );

        return User::create([
            'name' => ucfirst($roleName).' Test',
            'email' => uniqid($roleName.'-', true).'@example.test',
            'password' => 'password',
            'role_id' => $role->id,
        ]);
    }

    private function product(string $sku, string $name, int $stock, int $minimum): Product
    {
        return Product::create([
            'sku' => $sku,
            'name' => $name,
            'category' => 'Gamis',
            'price' => 500,
            'stock' => $stock,
            'min_stock' => $minimum,
        ]);
    }

    private function transaction(User $cashier, string $code, int $total, mixed $createdAt = null): Transaction
    {
        return Transaction::create([
            'code' => $code,
            'cashier_id' => $cashier->id,
            'total' => $total,
            'paid' => $total,
            'change' => 0,
            'payment_method' => Transaction::METHOD_CASH,
            'created_at' => $createdAt ?? now(),
            'updated_at' => $createdAt ?? now(),
        ]);
    }
}
