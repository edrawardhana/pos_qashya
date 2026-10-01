<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Qashya POS</title>
</head>
<body>
    <h2>Register</h2>

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Konfirmasi password" required>
        <select name="role" required>
            <option value="kasir">Kasir</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Daftar</button>
    </form>

    <a href="{{ route('login') }}">Sudah punya akun? Login</a>
</body>
</html>