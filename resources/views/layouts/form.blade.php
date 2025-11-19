{{-- layout "standalone", khusus untuk formulir.
     Tidak ada Navbar, tidak ada Footer --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - SIPMAMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <header class="py-6 bg-white shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-600">
                SIPMAMA
            </a>
        </div>
    </header>

    <main class="py-12">
        @yield('content')
    </main>

</body>
</html>