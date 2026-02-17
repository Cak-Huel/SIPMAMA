@extends('layouts.admin')

@section('header', 'Kelola Informasi & Konten')

@section('content')

<div x-data="{ activeTab: '{{ Auth::user()->role == 'admin' ? 'lowongan' : 'konten' }}' }">

    <!-- NAVIGASI TAB -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            @can('admin-only')
                <button @click="activeTab = 'lowongan'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'lowongan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'lowongan' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Kelola Kuota Lowongan
                </button>
            @endcan
                <button @click="activeTab = 'konten'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'konten', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'konten' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Kelola Konten Statis
                </button>
                <button @click="activeTab = 'galeri'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'galeri', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'galeri' }"
                        class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                    Kelola Galeri
                </button>
                <button @click="activeTab = 'faq'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'faq', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'faq' }"
                        class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                    Kelola FAQ
                </button>
        </nav>
    </div>

    <!-- TAB CONTENT: LOWONGAN -->
   @can('admin-only') 
        <div x-show="activeTab === 'lowongan'" style="display: none;">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-blue-800">Kuota Default (Global)</h3>
                    <p class="text-sm text-blue-600">Angka ini digunakan jika Anda tidak mengatur kuota khusus pada bulan tertentu.</p>
                </div>
                <form method="POST" action="{{ route('admin.informasi.kuota.global') }}" class="flex items-center gap-2">
                    @csrf
                    <input type="number" name="kuota_global" value="{{ $kuotaSetting->isi_teks }}" min="1" class="w-20 text-center border-blue-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-bold">Set Default</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Terisi</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Sisa</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Atur Kuota Max</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lowonganList as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $item->nama_bulan }}</span>
                                @if($item->is_custom)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Khusus
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $item->terisi }} org
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold {{ $item->status_color }}">{{ $item->sisa }}</span>
                            </td>

                            <form method="POST" action="{{ route('admin.informasi.kuota.periode') }}">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ $item->bulan }}">
                                <input type="hidden" name="tahun" value="{{ $item->tahun }}">

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="number" name="kuota" value="{{ $item->kuota_max }}" min="0" 
                                        class="w-20 text-center border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 font-bold {{ $item->is_custom ? 'bg-purple-50 border-purple-300 text-purple-700' : '' }}">
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Simpan
                                    </button>
                                </td>
                            </form>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endcan

    <!-- TAB CONTENT: KONTEN -->
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

    <!-- TAB CONTENT: GALERI -->
    <div x-show="activeTab === 'galeri'" style="display: none;">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Upload Foto Kegiatan</h3>

                    <form method="POST" action="{{ route('admin.informasi.galeri.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan</label>
                                <input type="text" name="judul" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2" placeholder="Contoh: Kunjungan Lapangan" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Periode / Tanggal</label>
                                <input type="text" name="periode" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2" placeholder="Contoh: Januari 2025" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">File Foto</label>
                                <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                Upload ke Galeri
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Galeri</h3>

                    @if($galeris->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($galeris as $item)
                                <div class="group relative border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">

                                    <div class="aspect-w-4 aspect-h-3 bg-gray-200">
                                        <img src="{{ Storage::url($item->foto_path) }}" alt="{{ $item->judul }}" class="object-cover w-full h-48">
                                    </div>

                                    <div class="p-3">
                                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $item->periode }}</span>
                                        <h4 class="font-bold text-gray-800 mt-1 text-sm truncate">{{ $item->judul }}</h4>
                                    </div>

                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <form method="POST" action="{{ route('admin.informasi.galeri.destroy', $item->id) }}" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            Belum ada foto di galeri.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- TAB CONTENT: FAQ -->
    <div x-show="activeTab === 'faq'" style="display: none;">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Pertanyaan</h3>

                    <form method="POST" action="{{ route('admin.informasi.faq.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                                <input type="text" name="pertanyaan" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2" placeholder="Contoh: Apakah dapat gaji?" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban</label>
                                <textarea name="jawaban" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2" placeholder="Jelaskan jawaban..." required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                                Tambah FAQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanya Jawab</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($faqs as $faq)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $faq->pertanyaan }}</p>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $faq->jawaban }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.informasi.faq.destroy', $faq->id) }}" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada FAQ yang ditambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection