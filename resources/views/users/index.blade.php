@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="page-header">
    <h1>Kelola Pengguna</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+Tambah Pengguna</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role?->label ?? '-' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm">Edit</a>
                    @if ($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline"
                            onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada pengguna.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $users->links() }}
@endsection