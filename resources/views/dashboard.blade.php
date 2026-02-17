{{-- template main.blade.php --}}
@extends('layouts.main')

{{-- "mengisi" ke dalam @yield('content') di template --}}
@section('content')

    <header class="relative bg-cover bg-center h-[450px]" style="background-image: url('{{ asset('images/Gedung.jpg') }}');">
        <div class="absolute inset-0 bg-blue-700/80 bg-gradient-to-r from-blue-800/80 to-blue-600/70" style="
    background-color: rgb(29 78 216 / -0.2);"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-4xl md:text-4xl font-bold mb-2 text-justify-center">
                Selamat Datang
            </h1>
            <h1 class="text-4xl md:text-5xl font-bold mb-1 text-justify-center">
                SIPMAMA
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold mb-1 text-justify-center">
                Sistem Penerimaan Mahasiswa Magang
            </h2>
            <p class="text-lg md:text-xl mb-8 max-w-2xl">
                Wujudkan pengalaman praktis Anda bersama kami.
            </p>
            @auth
                {{-- Jika Lolos & Masih Periode Magang --}}
                @if(Auth::user()->pendaftar && Auth::user()->pendaftar->status == 'lolos')
                    <a href="{{ route('presensi.index') }}" class="bg-green-500 text-white font-bold py-3 px-8 rounded-lg text-lg hover:bg-green-600 shadow-lg transform transition-transform hover:scale-105">
                        📍 Isi Presensi Hari Ini
                    </a>
                    @else
                    {{-- Tombol Lama (Daftar Magang) --}}
                    <a href="{{ route('pendaftaran.form') }}" class="bg-white text-blue-700 font-bold py-3 px-8 rounded-lg text-lg hover:bg-gray-100 shadow-lg transform transition-transform hover:scale-105">
                        Daftar Magang Sekarang
                    </a>
                    @endif
                @else
                {{-- Jika Belum Login (Tombol Daftar Lama) --}}
                <a href="{{ route('login') }}" class="bg-white text-blue-700 font-bold py-3 px-8 rounded-lg text-lg hover:bg-gray-100 shadow-lg transform transition-transform hover:scale-105">
                    Daftar Magang Sekarang
                </a>
            @endauth
        </div>
    </header>

    <main>
        <section class="py-16 bg-white border-b border-gray-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    
                    <div class="md:w-1/2">
                        <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider text-blue-600 uppercase bg-blue-100 rounded-full">
                            Tentang Sistem
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Apa itu <span class="text-blue-600">SIPMAMA</span>?
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6 text-justify">
                            <strong>SIPMAMA</strong> (Sistem Penerimaan Mahasiswa Magang) adalah platform resmi Badan Pusat Statistik (BPS) Kabupaten Lamongan 
                            yang dirancang khusus untuk memfasilitasi proses pendaftaran, verifikasi, dan monitoring kegiatan magang mahasiswa
                        <p class="text-gray-600 text-lg leading-relaxed text-justify">
                            Sistem ini bertujuan untuk menjembatani kebutuhan antara institusi pendidikan dan peluang praktik kerja di lingkungan BPS secara efisien, transparan, dan terstruktur.
                        </p>
                    </div>

                    <div class="md:w-1/2 flex justify-center">
                        <div class="relative w-full max-w-md">
                            <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                            
                            <img src="{{ asset('images/bps.jpg') }}" 
                                alt="Ilustrasi SIPMAMA" 
                                class="relative rounded-2xl shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500 border-4 border-white">
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    🚀 Mengapa Magang di BPS?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md text-center border-b-4 border-blue-500">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Akses Data Eksklusif</h3>
                        <p class="text-gray-600">Dapatkan wawasan langsung dari data statistik nasional.</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md text-center border-b-4 border-blue-500">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Mentor Berpengalaman</h3>
                        <p class="text-gray-600">Dibimbing langsung oleh para ahli dan praktisi di bidang statistika.</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md text-center border-b-4 border-blue-500">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Pengalaman Kerja Nyata</h3>
                        <p class="text-gray-600">Terlibat dalam proyek dan survei nyata yang berdampak.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-blue-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    🗓️ Status Kuota Magang Saat Ini
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="bg-white p-6 rounded-lg shadow-md text-center border-t-4 border-blue-500">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Magang Berjalan</h3>
                        <div class="flex justify-center items-baseline">
                            <span class="text-4xl font-bold text-blue-700">{{ $magangBerjalan }}</span>
                            <span class="ml-2 text-gray-500">Orang</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Peserta aktif saat ini</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center border-t-4 border-gray-500">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Kapasitas Maksimum</h3>
                        <div class="flex justify-center items-baseline">
                            <span class="text-4xl font-bold text-gray-700">{{ $kuotaMax }}</span>
                            <span class="ml-2 text-gray-500">Orang</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Total kapasitas Lab/Kantor</p>
                    </div>

                    @php
                        $isAvailableNow = $perkiraanTersedia == 'Sekarang';
                        $cardColor = $isAvailableNow ? 'border-green-500' : 'border-yellow-500';
                        $textColor = $isAvailableNow ? 'text-green-600' : 'text-yellow-600';
                    @endphp

                    <div class="bg-white p-6 rounded-lg shadow-md text-center border-t-4 {{ $cardColor }}">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Perkiraan Kuota Tersedia</h3>
                        
                        @if($isAvailableNow)
                            <p class="text-3xl font-bold {{ $textColor }} mt-2">
                                {{ $perkiraanTersedia }}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">Anda bisa mendaftar hari ini!</p>
                        @else
                            <p class="text-3xl font-bold {{ $textColor }} mt-2">
                                {{ $perkiraanTersedia }}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">Slot kosong berikutnya</p>
                        @endif
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    🗺️ Langkah-Langkah Pendaftaran
                </h2>
                <ol class="space-y-6">
                    <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">1</span><span class="text-lg text-gray-700">Daftar Akun (Jika belum memiliki).</span></li>
                    <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">2</span><span class="text-lg text-gray-700">Lihat Lowongan & Syarat Ketentuan.</span></li>
                    <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">3</span><span class="text-lg text-gray-700">Isi Formulir & Upload Dokumen Wajib.</span></li>
                    <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">4</span><span class="text-lg text-gray-700">Tunggu proses verifikasi oleh Admin.</span></li>
                    <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">5</span><span class="text-lg text-gray-700">Cek status penerimaan di Halaman Profil.</span></li>
                </ol>
            </div>
        </section>
    </main>

@endsection
{{-- 3. Selesai "mengisi" konten --}}