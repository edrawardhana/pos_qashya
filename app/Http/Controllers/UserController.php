<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // LIST
    public function index()
    {
        $users = User::with('role')->orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }

    // CREATE FORM
    public function create()
    {
        $roles = Role::orderBy('label')->get();
        return view('users.create', compact('roles'));
    }

    // STORE (INSERT)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // EDIT FORM
    public function edit(User $user)
    {
        $roles = Role::orderBy('label')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    // UPDATE
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        // Cegah Admin terakhir hilang hak akses
        if ($user->isAdmin() && (int) $data['role_id'] !== $user->role_id && $this->isLastAdmin($user)) {
            return back()->withInput()
                ->with('error', 'Tidak dapat mengubah role Admin terakhir.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->with('error', 'Tidak dapat menghapus Admin terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    private function isLastAdmin(User $user): bool
    {
        return User::whereHas('role', fn ($q) => $q->where('name', Role::ADMIN))
            ->where('id', '!=', $user->id)
            ->doesntExist();
    }
}