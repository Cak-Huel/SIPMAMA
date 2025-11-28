@extends('layouts.admin')

@section('header', 'Kelola Informasi & Konten')

@section('content')

<div x-data="{ activeTab: 'lowongan' }"> 

    <!-- NAVIGASI TAB -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            <button @click="activeTab = 'lowongan'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'lowongan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'lowongan' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Ketersediaan Lowongan
            </button>
            <button @click="activeTab = 'konten'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'konten', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'konten' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Konten Statis
            </button>
        </nav>
    </div>

    <div x-show="activeTab === 'lowongan'" style="display: none;">
       <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pengaturan Kapasitas</h3>
            <form method="POST" action="{{ route('admin.informasi.kuota.update') }}" class="flex items-end gap-4">
                @csrf
                <div class="w-full max-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Kuota Magang BPS (Global)</label>
                    <input type="number" name="kuota_global" value="{{ $kuotaSetting->isi_teks }}" min="1" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 text-lg font-bold text-center">
                    <p class="text-xs text-gray-500 mt-1">Jumlah maksimal mahasiswa magang dalam satu waktu.</p>
                </div>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-blue-700 transition-colors mb-[2px]">
                    Simpan Kapasitas
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <h4 class="font-semibold text-gray-700">Simulasi Ketersediaan (12 Bulan Kedepan)</h4>
                <p class="text-sm text-gray-500">
                    Tabel ini dihitung otomatis berdasarkan tanggal mulai & selesai mahasiswa yang berstatus <strong>LOLOS</strong>.
                </p>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan / Periode</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kapasitas Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Terisi (Estimasi)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ketersediaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lowonganList as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->nama_bulan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $item->kuota_max }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ $item->terisi }} Orang
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-bold {{ $item->status_color }}">
                            {{ $item->sisa }} Kursi
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-xs font-bold px-2 py-1 rounded {{ $item->sisa == 0 ? 'bg-red-100 text-red-700' : ($item->sisa <= 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ strtoupper($item->status_label) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="activeTab === 'konten'" style="display: none;">
       <div class="bg-white rounded-lg shadow-sm p-6">
            
            <form method="POST" action="{{ route('admin.informasi.konten.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Syarat & Ketentuan (Halaman Informasi)</label>
                            <textarea name="syarat" rows="10" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3" placeholder="Tulis syarat poin per poin...">{{ $syarat->isi_teks }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Gunakan tanda minus (-) untuk membuat bullet point manual.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poster Utama</label>
                        
                        <div class="mb-4 border-2 border-dashed border-gray-300 rounded-lg p-2 flex justify-center items-center bg-gray-50 min-h-[300px]">
                            @if($poster->file_poster)
                                <img src="{{ Storage::url($poster->file_poster) }}" alt="Poster" class="max-h-80 rounded shadow">
                            @else
                                <div class="text-center text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    <p class="mt-1 text-sm">Belum ada poster</p>
                                </div>
                            @endif
                        </div>

                        <input type="file" name="poster" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-2">Format: JPG/PNG. Maks 2MB. Gambar ini akan muncul di halaman Informasi.</p>
                    </div>

                </div>

                <div class="mt-8 border-t pt-6 text-right">
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Perubahan Konten
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection