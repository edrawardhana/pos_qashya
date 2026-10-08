@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<h1>Edit Pengguna</h1>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="role_id">Role</label>
        <select id="role_id" name="role_id" required>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->label }}</option>
            @endforeach
        </select>
        @error('role_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="password">Password Baru <small>(kosongkan jika tidak diganti)</small></label>
        <input type="password" id="password" name="password">
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation">
    </div>

    <button type="submit" class="btn btn-primary">Perbarui</button>
    <a href="{{ route('users.index') }}" class="btn">Batal</a>
</form>
@endsection