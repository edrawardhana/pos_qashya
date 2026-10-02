<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Qashya Collection</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .login-container {
            min-height: 100vh;
        }
        .left-panel {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .feature-box {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #333;
        }
        .card-custom {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .btn-dark-custom {
            background-color: #000000;
            color: #ffffff;
            border-radius: 6px;
            padding: 10px;
            font-weight: 500;
        }
        .btn-dark-custom:hover {
            background-color: #222222;
            color: #ffffff;
        }
        .demo-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="container-fluid login-container">
        <div class="row h-100 min-vh-100 align-items-center">
            
            <!-- KOLOM KIRI: Informasi & Branding -->
            <div class="col-lg-6 left-panel d-none d-lg-flex flex-column justify-content-between p-5 min-vh-100">
                <div>
                    <!-- Logo / Brand Header -->
                    <div class="d-flex align-items-center gap-2 mb-5">
                        <div class="border border-dark rounded p-2 d-flex align-items-center justify-content-center bg-white" style="width: 38px; height: 38px;">
                            <i class="bi bi-shop text-dark"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">Qashya Collection</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Point of Sales</small>
                        </div>
                    </div>

                    <!-- Main Headline -->
                    <div class="mb-5" style="max-width: 480px;">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Sistem POS berbasis web</span>
                        <h1 class="fw-bold text-dark display-6 mt-2 mb-4" style="line-height: 1.2;">Kelola penjualan, stok & laporan dalam satu tempat.</h1>
                        
                        <!-- Feature Pills Grid -->
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="feature-box">Transaksi penjualan real-time</div>
                            </div>
                            <div class="col-6">
                                <div class="feature-box">Stok otomatis & notifikasi minimum</div>
                            </div>
                            <div class="col-6">
                                <div class="feature-box">Laporan harian, mingguan, bulanan</div>
                            </div>
                            <div class="col-6">
                                <div class="feature-box">Hak akses Admin & Kasir</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Copyright -->
                <div>
                    <small class="text-muted" style="font-size: 0.75rem;">&copy; 2026 Qashya Collection by wardhanaedra</small>
                </div>
            </div>

            <!-- KOLOM KANAN: Form Login & Akun Demo -->
            <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center p-4">
                <div class="w-100" style="max-width: 400px;">
                    
                    <!-- Form Card -->
                    <div class="card card-custom p-4 mb-3">
                        <div class="mb-4">
                            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Masuk ke akun</h4>
                            <p class="text-muted mb-0" style="font-size: 0.85rem;">Gunakan username dan password yang diberikan admin.</p>
                        </div>

                        <!-- Session Success Notification -->
                        @if (session('success'))
                            <div class="alert alert-success py-2 mb-3" style="font-size: 0.85rem;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Error Notification -->
                        @error('email')
                            <div class="alert alert-danger py-2 mb-3" style="font-size: 0.85rem;">
                                {{ $message }}
                            </div>
                        @endif

                        @if($errors->any() && !$errors->has('email'))
                            <div class="alert alert-danger py-2 mb-3" style="font-size: 0.85rem;">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-dark fw-medium" style="font-size: 0.85rem;">Email / Username</label>
                                <input type="email" name="email" class="form-control form-control-sm py-2" value="{{ old('email') }}" placeholder="mis. kasir@qashya.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-dark fw-medium" style="font-size: 0.85rem;">Password</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" name="password" id="password" class="form-control py-2" placeholder="********" required>
                                    <span class="input-group-text bg-white text-muted" style="cursor: pointer;" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark-custom w-100 mb-3">Masuk</button>
                        </form>

                        <div class="text-center" style="font-size: 0.85rem;">
                            <span class="text-muted">Belum punya akun?</span> <a href="{{ route('register') }}" class="text-dark fw-semibold text-decoration-none">Daftar</a>
                        </div>
                    </div>

                    <!-- Akun Demo Box (Sesuai Figma) -->
                    <div class="demo-card p-3">
                        <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">Akun demo</small>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light">
                                    <strong class="d-block text-dark" style="font-size: 0.8rem;">Admin</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">admin / admin123</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light">
                                    <strong class="d-block text-dark" style="font-size: 0.8rem;">Kasir</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">kasir / kasir123</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script sederhana untuk Toggle Show/Hide Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>