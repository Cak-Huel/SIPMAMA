{{-- template main.blade.php --}}
@extends('layouts.main')

{{-- "mengisi" ke dalam @yield('content') di template --}}
@section('content')

    <header class="relative bg-cover bg-center h-[450px]" style="background-image: url('https://i.imgur.com/gL9n1vC.jpg');">
        <div class="absolute inset-0 bg-blue-700/80 bg-gradient-to-r from-blue-800/80 to-blue-600/70"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-justify-center">
                Selamat Datang
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold mb-1 text-justify-center">
                Sistem Informasi Pendaftaran Magang Mahasiswa
            </h2>
            <p class="text-lg md:text-xl mb-8 max-w-2xl">
                Wujudkan pengalaman praktis Anda bersama kami.
            </p>
            <a href="{{ route('pendaftaran.form') }}" class="bg-white text-blue-700 font-bold py-3 px-8 rounded-lg text-lg hover:bg-gray-100 shadow-lg transform transition-transform hover:scale-105">
                Daftar Magang Sekarang
            </a>
        </div>
    </header>

    <main>
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
                    🗓️ Info Lowongan Terbaru
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Periode Magang Aktif</h3>
                        <p class="text-3xl font-bold text-blue-700">Agustus - Nov 2025</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Kuota Tersedia</h3>
                        <p class="text-3xl font-bold text-blue-700">14 <span class="text-lg">Orang</span></p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg shadow-md text-center border-2 border-yellow-300">
                        <h3 class="text-sm font-semibold text-yellow-600 uppercase mb-2">Batas Pendaftaran</h3>
                        <p class="text-3xl font-bold text-yellow-700">30 Juli 2025</p>
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