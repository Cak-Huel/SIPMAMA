{{-- "template" utama (Navbar, Footer). --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPMAMA BPS KABUPATEN LAMONGAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="#" class="text-2xl font-bold text-blue-600">SIPMAMA</a>
                </div>
                <div class="flex items-center space-x-8">
                    <div class="hidden md:flex items-center--justify-center space-x-6">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">Beranda</a>
                        <a href="{{ route('informasi') }}" class="text-gray-700 hover:text-blue-600 font-medium">Informasi</a>
                    
                        <div class="relative group pb-1">
                            <button class="text-gray-700 hover:text-blue-600 font-medium flex items-center">
                                <span>Tentang</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div class="absolute hidden group-hover:block 
                                        bg-white rounded-lg shadow-lg border 
                                        min-w-max w-60 z-50 mt-1">
                                
                                <a href="https://lamongankab.bps.go.id/id" target="_blank" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                BPS Kabupaten Lamongan
                                </a>
                                 <a href="https://ppid.bps.go.id/app/konten/3524/Profil-BPS.html" target="_blank" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                Profil BPS
                                </a>
                                <a href="https://ppid.bps.go.id/app/konten/3524/Profil-PPID.html" target="_blank" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                Profil PPID
                                </a>
                            </div>
                        </div>
                    </div>
                    @auth
                    <a href="{{ route('profil.show') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium">
                            Logout
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                        Masuk / Daftar
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav> @if (session('success'))
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif
@if (session('error'))
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
@endif
@if (session('info'))
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    </div>
@endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-300 pt-12 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Alamat Instansi</h4>
                    <p class="text-sm">Badan Pusat Statistik (BPS)</p>
                    <p class="text-sm">Jl. Dr. Sutomo No. 6-8, Jakarta Pusat 10710</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Kontak & Dukungan</h4>
                    <p class="text-sm">Telepon/WA: +62 812-3456-7890</p>
                    <p class="text-sm">Email: magang@bps.go.id</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white">Facebook</a>
                        <a href="#" class="hover:text-white">Instagram</a>
                        <a href="#" class="hover:text-white">X</a>
                        <a href="#" class="hover:text-white">Youtube</a>
                        
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-sm text-gray-400">&copy; 2025 SIPMAMA BPS. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>