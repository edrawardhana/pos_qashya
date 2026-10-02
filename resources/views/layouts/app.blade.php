<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Manajemen Produk' }} - Qashya Collection</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex h-screen overflow-hidden">

    <!-- Panggil Komponen Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shrink-0">
            <div>
                <h2 class="font-semibold text-gray-800 text-sm">Manajemen Produk</h2>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-gray-100 px-3 py-1.5 rounded-md font-medium text-gray-600">Admin</span>
                <button class="text-xs text-gray-600 hover:text-gray-900 font-medium flex items-center gap-1.5 border border-gray-200 px-3 py-1.5 rounded-md">
                    Keluar
                </button>
            </div>
        </header>

        <!-- Dynamic Content Body (Tempat view index, create, edit masuk) -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>