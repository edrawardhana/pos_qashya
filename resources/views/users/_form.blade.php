@php $isEdit = isset($user); @endphp

<form method="POST"
        action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}"
        class="grid max-w-2xl gap-4 rounded bg-white p-6 shadow">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @error('form')
        <p class="rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ $message }}</p>
    @enderror

    <div>
        <label for="name" class="block text-sm font-medium">Nama lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}"
            placeholder="mis. Siti Rahmawati" class="mt-1 w-full rounded border px-3 py-2">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 w-full rounded border px-3 py-2">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="role_id" class="block text-sm font-medium">Role</label>
            <select id="role_id" name="role_id" class="mt-1 w-full rounded border px-3 py-2">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected((int) old('role_id', $user->role_id ?? '') === $role->id)>
                        {{ $role->label }}
                    </option>
                @endforeach
            </select>
            @error('role_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                class="mt-1 w-full rounded border px-3 py-2">
            <p class="mt-1 text-xs text-gray-500">
                {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti password. Minimal 6 karakter.' : 'Minimal 6 karakter.' }}
            </p>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))>
        Akun aktif
    </label>

    <div class="flex gap-3">
        <button type="submit" class="rounded bg-black px-4 py-2 text-sm text-white">Simpan</button>
        <a href="{{ route('users.index') }}" class="rounded border px-4 py-2 text-sm">Batal</a>
    </div>
</form>