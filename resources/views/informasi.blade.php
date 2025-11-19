{{-- layout induk (otomatis dapat Navbar & Footer) --}}
@extends('layouts.main')

{{-- CSS khusus untuk smooth scroll di halaman ini --}}
@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

{{-- "mengisi" konten ke dalam layout --}}
@section('content')

    <header class="relative bg-cover bg-center h-80" style="background-image: url('https://via.placeholder.com/1600x600/3B82F6/FFFFFF?text=Banner+Penerimaan+Magang+BPS');">
        <div class="absolute inset-0 bg-blue-700/80 bg-gradient-to-r from-blue-800/80 to-blue-600/70"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-4xl md:text-5xl font-bold">
                Informasi Detail Program Magang
            </h1>
        </div>
    </header>
    
    <div class="bg-white shadow-md sticky top-16 z-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-center space-x-6 md:space-x-10 py-4">
                <a href="#syarat" class="text-sm font-medium text-gray-600 hover:text-blue-600">Syarat & Ketentuan</a>
                <a href="#kuota" class="text-sm font-medium text-gray-600 hover:text-blue-600">Periode & Kuota</a>
                <a href="#alur" class="text-sm font-medium text-gray-600 hover:text-blue-600">Alur Pendaftaran</a>
            </nav>
        </div>
    </div>

    <section id="syarat" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                📑 Syarat dan Ketentuan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-start">
                
                <div>
                    <img src="https://via.placeholder.com/600x850/EBF8FF/3B82F6?text=Contoh+Poster+Informasi+Magang" 
                         alt="Poster Syarat & Ketentuan Magang BPS" 
                         class="w-full rounded-lg shadow-lg">
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <h3 class="text-2xl font-semibold mb-4">Persyaratan Akademik</h3>
                    <ul class="list-disc list-outside ml-5 space-y-2 text-gray-700">
                        <li>Mahasiswa aktif (D3/D4/S1) minimal semester 5.</li>
                        <li>IPK minimal 3.00 dari skala 4.00.</li>
                        <li>Berasal dari program studi yang relevan (Statistika, IT, Ekonomi, dll).</li>
                        <li>Mampu beradaptasi dan bekerja dalam tim.</li>
                    </ul>
                    
                    <h3 class="text-2xl font-semibold mt-8 mb-4">Dokumen Wajib (PDF)</h3>
                    <ul class="list-disc list-outside ml-5 space-y-2 text-gray-700">
                        <li>Proposal Magang yang disetujui Dosen Pembimbing.</li>
                        <li>Surat Rekomendasi/Pengantar resmi dari Universitas.</li>
                        <li>Transkrip Nilai terbaru.</li>
                        <li>Curriculum Vitae (CV) terbaru.</li>
                        <li>Semua file diunggah dalam format PDF (maks. 5MB per file).</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <section id="kuota" class="py-16 bg-blue-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                🗓️ Status Kuota dan Periode Magang
            </h2>
            
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="w-full min-w-max bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-3 text-left text-sm font-semibold text-gray-700">Bulan / Tahun</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-700">Kuota Awal</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-700">Kuota Tersisa</th>
                            <th class="p-3 text-center text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="p-3 font-medium text-gray-800">Januari 2026</td>
                            <td class="p-3 text-center text-gray-700">10</td>
                            <td class="p-3 text-center text-gray-700">0</td>
                            <td class="p-3 text-center">
                                <span class="bg-red-100 text-red-700 font-bold py-1 px-3 rounded-full text-xs">
                                    PENUH
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium text-gray-800">Februari 2026</td>
                            <td class="p-3 text-center text-gray-700">10</td>
                            <td class="p-3 text-center text-gray-700">5</td>
                            <td class="p-3 text-center">
                                <span class="bg-green-100 text-green-700 font-bold py-1 px-3 rounded-full text-xs">
                                    TERSEDIA
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium text-gray-800">Maret 2026</td>
                            <td class="p-3 text-center text-gray-700">10</td>
                            <td class="p-3 text-center text-gray-700">10</td>
                            <td class="p-3 text-center">
                                <span class="bg-green-100 text-green-700 font-bold py-1 px-3 rounded-full text-xs">
                                    TERSEDIA
                                </span>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="alur" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                🗺️ Alur Pendaftaran
            </h2>
            <ol class="space-y-6">
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">1</span><span class="text-lg text-gray-700">Daftar Akun (Jika belum memiliki).</span></li>
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">2</span><span class="text-lg text-gray-700">Lihat Lowongan & Syarat Ketentuan di halaman ini.</span></li>
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">3</span><span class="text-lg text-gray-700">Login dan klik tombol "Daftar Magang Sekarang".</span></li>
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">4</span><span class="text-lg text-gray-700">Isi Formulir & Upload Dokumen Wajib.</span></li>
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">5</span><span class="text-lg text-gray-700">Tunggu proses verifikasi oleh Admin.</span></li>
                <li class="flex items-center"><span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold text-lg mr-4">6</span><span class="text-lg text-gray-700">Cek status penerimaan di Halaman Profil.</span></li>
            </ol>

        </div>
    </section>

@endsection
{{-- 3. Selesai "mengisi" konten --}}