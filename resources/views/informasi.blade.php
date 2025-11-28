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

    <header class="relative bg-gradient-to-r from-blue-600 to-blue-800 h-80">
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
                    {{-- Ambil data poster dari controller/view composer --}}
                    @php
                        // Ambil path poster dari database (karena ini view publik, kita query manual dikit disini atau pass dari controller)
                        $posterData = \App\Models\Pengaturan::where('key', 'poster_utama')->first();
                    @endphp

                    @if($posterData && $posterData->file_poster)
                        <img src="{{ Storage::url($posterData->file_poster) }}" 
                             alt="Poster Syarat & Ketentuan" 
                             class="w-full rounded-lg shadow-lg hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                            Poster Belum Tersedia
                        </div>
                    @endif
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <h3 class="text-2xl font-semibold mb-4">Persyaratan & Ketentuan</h3>
                    
                    {{-- LOGIKA PINTAR: Mengubah tanda (-) menjadi Bullet HTML --}}
                    <ul class="list-disc list-outside ml-5 space-y-2 text-gray-700">
                        @if($syarat)
                            @foreach(explode(PHP_EOL, $syarat->isi_teks) as $poin)
                                {{-- Hanya tampilkan jika baris tidak kosong --}}
                                @if(trim($poin) != '') 
                                    {{-- Hapus tanda '-' di awal kalimat jika ada, lalu tampilkan --}}
                                    <li>{{ ltrim(trim($poin), '- ') }}</li>
                                @endif
                            @endforeach
                        @else
                            <li>Belum ada informasi syarat.</li>
                        @endif
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
                            <th class="p-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Bulan / Tahun</th>
                            <th class="p-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Kuota Awal</th>
                            <th class="p-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Kuota Tersisa</th>
                            <th class="p-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        
                        {{-- LOOPING DATA DARI CONTROLLER --}}
                        @foreach($jadwalKuota as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-800">
                                {{ $item->periode }}
                            </td>
                            
                            <td class="p-4 text-center text-gray-600 font-medium">
                                {{ $item->kuota_awal }}
                            </td>
                            
                            <td class="p-4 text-center">
                                <span class="font-bold text-lg {{ $item->sisa == 0 ? 'text-red-600' : 'text-gray-800' }}">
                                    {{ $item->sisa }}
                                </span>
                            </td>
                            
                            <td class="p-4 text-center">
                                <span class="{{ $item->status_color }} font-bold py-1 px-3 rounded-full text-xs tracking-wide border border-opacity-20 border-current">
                                    {{ $item->status_text }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        {{-- AKHIR LOOPING --}}

                    </tbody>
                </table>
            </div>
            
            <p class="text-center text-gray-500 text-sm mt-4">
                *Status diperbarui secara otomatis berdasarkan jumlah peserta aktif.
            </p>
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