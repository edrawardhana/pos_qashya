<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Qashya POS</title>
</head>
<body>
    <h2>Login</h2>

    @if (session('success')) <p>{{ session('success') }}</p> @endif
    @error('email') <p style="color:red">{{ $message }}</p> @enderror

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <label><input type="checkbox" name="remember"> Ingat saya</label>
        <button type="submit">Masuk</button>
    </form>

    <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
</body>
</html>