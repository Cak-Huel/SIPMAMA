{{-- "template" utama (Navbar, Footer). --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPMAMA BPS KABUPATEN LAMONGAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ open: false }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">SIPMAMA</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8" style="padding-top: 10px;">
                    <div class="hidden md:flex items-center--justify-center space-x-6">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">Beranda</a>
                        <a href="{{ route('informasi') }}" class="text-gray-700 hover:text-blue-600 font-medium">Informasi</a>
                        <a href="{{ route('galeri.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Galeri</a>
                        <a href="{{ route('faq.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">FAQ</a>
                        
                        @auth
                            @if(Auth::user()->pendaftar && Auth::user()->pendaftar->status == 'lolos')
                                <a href="{{ route('presensi.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Presensi</a>
                            @endif
                        @endauth
        
                        <div class="relative group pb-2">
                            <button class="text-gray-700 hover:text-blue-600 font-medium flex items-center">
                                <span>Tentang</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute hidden group-hover:block bg-white rounded-lg shadow-lg border min-w-max w-48 z-50 mt-1 right-0">
                                <a href="https://lamongankab.bps.go.id/" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Web BPS Lamongan</a>
                                <a href="https://ppid.bps.go.id/app/konten/3524/Profil-BPS.html" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Profil BPS</a>
                                <a href="https://ppid.bps.go.id/app/konten/3524/Profil-PPID.html" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Profil PPID</a>
                            </div>
                        </div>
        
                        @auth
                            <div class="flex items-center space-x-4 border-l pl-4 ml-4">
                                <a href="{{ route('profil.show') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                            Profil Saya
                                </a>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Login</a>
                        @endauth
                    </div>
                </div>
    
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    
        <div x-show="open" class="md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Beranda</a>
                <a href="{{ route('informasi') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Informasi</a>
                <a href="{{ route('galeri.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Galeri</a>
                <a href="{{ route('faq.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">FAQ</a>
               @auth
                    @if(Auth::user()->pendaftar && Auth::user()->pendaftar->status == 'lolos')
                        <a href="{{ route('presensi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Presensi</a>
                    @endif
                @endauth

                <div class="border-t border-gray-200 mt-2 pt-2">
                    <p class="px-3 py-2 text-sm font-semibold text-gray-400">Tentang</p>
                    <a href="https://lamongankab.bps.go.id/" target="_blank" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Web BPS Lamongan</a>
                    <a href="https://ppid.bps.go.id/app/konten/3524/Profil-BPS.html" target="_blank" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Profil BPS</a>
                    <a href="https://ppid.bps.go.id/app/konten/3524/Profil-PPID.html" target="_blank" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Profil PPID</a>
                </div>
                
                @auth
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <a href="{{ route('profil.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600">Profil Saya</a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    
        {{-- NOTIFIKASI FLASH MESSAGE --}}
    <div class="fixed top-20 right-4 z-50 w-full max-w-sm space-y-2 pointer-events-none">
        
        {{-- 1. SUKSES (HIJAU) --}}
        @if (session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg flex items-start justify-between">
                
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                
                <button @click="show = false" class="text-green-700 hover:text-green-900 ml-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- 2. ERROR (MERAH) --}}
        @if (session('error'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg flex items-start justify-between">
                
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>

                <button @click="show = false" class="text-red-700 hover:text-red-900 ml-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- 3. INFO (BIRU) --}}
        @if (session('info'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-lg flex items-start justify-between">
                
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>

                <button @click="show = false" class="text-blue-700 hover:text-blue-900 ml-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif
        
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-300 pt-12 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Alamat Instansi</h4>
                    <p class="text-sm">Badan Pusat Statistik (BPS) Kabupaten Lamongan</p>
                    <p class="text-sm">Jl. Veteran No.185, Tlogoanyar, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur 62218</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Kontak & Dukungan</h4>
                    <p class="text-sm">Telp (0322) 3103310</p>
                    <p class="text-sm">Fax (0322) 3103311</p>
                    <p class="text-sm">Mailbox : bps3524@bps.go.id</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="https://m.facebook.com/profile.php?id=100024240907092" target="_blank" class="hover:text-white">Facebook</a>
                        <a href="https://instagram.com/bpslamongan?igshid=YmMyMTA2M2Y=" target="_blank" class="hover:text-white">Instagram</a>
                        <a href="http://twitter.com/bpslamongan3524" target="_blank" class="hover:text-white">X</a>
                        <a href="http://youtube.com/c/bpslamongan3524" target="_blank" class="hover:text-white">Youtube</a>
                        
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-sm text-gray-400">&copy; 2025 SIPMAMA BPS KABUPATEN LAMONGAN. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Tombol WA Admin --}}
    <a href="https://wa.me/+6281339708428" target="_blank" 
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-xl transition-transform transform hover:scale-110 flex items-center justify-center group"
       title="Hubungi Admin via WhatsApp">
        
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
        
        <span class="absolute right-16 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap hidden md:block">
            Hubungi Kami
        </span>
    </a>

    {{--s NOTIFIKASI KHUSUS + TOMBOL WA --}}
        @if (session('success_wa'))
            <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl p-8 max-w-md text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Data Berhasil Dikirim!</h3>
                    <p class="text-gray-500 mb-6">Silakan konfirmasi ke Admin agar berkas Anda segera diperiksa ulang.</p>
                    
                    <a href="{{ session('success_wa') }}" target="_blank" class="block w-full bg-green-500 text-white font-bold py-3 px-4 rounded hover:bg-green-600 transition-colors mb-3">
                        💬 Chat Admin via WhatsApp
                    </a>
                    
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 text-sm underline">
                        Tutup
                    </button>
                </div>
            </div>
        @endif
</body>
</html>