<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Qashya Collection</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm space-y-4 rounded-lg bg-white p-8 shadow">
        @csrf
        <h1 class="text-xl font-semibold">Masuk ke Qashya POS</h1>

        <div>
            <label for="email" class="block text-sm">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="mt-1 w-full rounded border px-3 py-2" autofocus>
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm">Password</label>
            <input id="password" type="password" name="password"
                class="mt-1 w-full rounded border px-3 py-2">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember"> Ingat saya
        </label>

        <button type="submit" class="w-full rounded bg-black py-2 text-white">Masuk</button>
    </form>
</body>
</html>