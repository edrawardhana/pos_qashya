@extends('layouts.app')

@section('title', 'Pengguna · Qashya POS')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Pengguna</h1>
    <a href="{{ route('users.create') }}" class="rounded bg-black px-4 py-2 text-sm text-white">+ Tambah pengguna</a>
</div>

@if (session('error'))
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

<form method="GET" action="{{ route('users.index') }}" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email"
        class="w-full max-w-sm rounded border px-3 py-2 text-sm">
    <button type="submit" class="rounded border px-4 py-2 text-sm">Cari</button>
</form>

<div class="overflow-x-auto rounded bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($users as $user)
                @php $isSelf = $user->is(auth()->user()); @endphp
                <tr>
                    <td class="px-4 py-2 font-medium">
                        {{ $user->name }}
                        @if ($isSelf) <span class="text-xs text-gray-500">(Anda)</span> @endif
                    </td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->role?->label }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('users.toggle', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" @disabled($isSelf)
                                    class="rounded px-2 py-1 text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }} disabled:opacity-50">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:underline">Ubah</a>
                        @unless ($isSelf)
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline"
                                onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:underline">Hapus</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>

<section class="mt-10">
    <h2 class="mb-3 text-lg font-semibold">Matriks hak akses</h2>
    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Fitur</th>
                    <th class="px-4 py-2 text-center">Admin</th>
                    <th class="px-4 py-2 text-center">Kasir</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($permissions as $row)
                    <tr>
                        <td class="px-4 py-2">{{ $row['feature'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $row['admin'] ? '✓' : '—' }}</td>
                        <td class="px-4 py-2 text-center">{{ $row['kasir'] ? '✓' : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection