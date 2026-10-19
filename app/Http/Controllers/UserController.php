<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class UserController extends Controller
{
    /** Matriks hak akses yang ditampilkan di halaman pengguna. */
    private const PERMISSIONS = [
        ['feature' => 'Kelola data produk (tambah, ubah, hapus)', 'admin' => true, 'kasir' => false],
        ['feature' => 'Lihat data produk (harga & stok)', 'admin' => true, 'kasir' => true],
        ['feature' => 'Kelola pengguna & hak akses', 'admin' => true, 'kasir' => false],
        ['feature' => 'Manajemen & riwayat stok', 'admin' => true, 'kasir' => false],
        ['feature' => 'Transaksi penjualan', 'admin' => false, 'kasir' => true],
        ['feature' => 'Laporan penjualan & export', 'admin' => true, 'kasir' => false],
        ['feature' => 'Riwayat transaksi', 'admin' => true, 'kasir' => true],
    ];

    public function index(Request $request): View
    {
        $users = User::query()
            ->with('role')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->toString();
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'permissions' => self::PERMISSIONS,
        ]);
    }

    public function create(): View
    {
        return view('users.create', ['roles' => Role::orderBy('label')->get()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('label')->get(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']); // kosong berarti password tidak diganti
        }

        $newRole = Role::find($data['role_id']);
        $blocked = $this->blockedReason($user, (bool) $data['is_active'], $newRole?->name === Role::ADMIN);

        if ($blocked) {
            return back()->withInput()->withErrors(['form' => $blocked]);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $newActive = ! $user->is_active;
        $blocked = $this->blockedReason($user, $newActive, $user->hasRole(Role::ADMIN));

        if ($blocked) {
            return back()->with('error', $blocked);
        }

        $user->update(['is_active' => $newActive]);

        return back()->with('status', $user->name.($newActive ? ' diaktifkan.' : ' dinonaktifkan.'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $blocked = $this->blockedReason($user, false, false);

        if ($blocked) {
            return back()->with('error', $blocked);
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil dihapus.');
    }

    /**
     * Mengembalikan alasan perubahan ditolak, atau null jika boleh.
     */
    private function blockedReason(User $user, bool $willBeActive, bool $willBeAdmin): ?string
    {
        if ($user->is(auth()->user()) && (! $willBeActive || ! $willBeAdmin)) {
            return 'Anda tidak bisa menonaktifkan, menurunkan role, atau menghapus akun sendiri.';
        }

        $isActiveAdmin = $user->is_active && $user->hasRole(Role::ADMIN);
        $keepsAdmin = $willBeActive && $willBeAdmin;

        if ($isActiveAdmin && ! $keepsAdmin && $this->activeAdminCount() <= 1) {
            return 'Harus ada minimal satu admin yang aktif.';
        }

        return null;
    }

    private function activeAdminCount(): int
    {
        return User::where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('name', Role::ADMIN))
            ->count();
    }
}