{{-- untuk "memakai" layout 'layouts.form' --}}
@extends('layouts.form')

{{-- "mengisi" ke dalam @yield('content') --}}
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">

        <div>
            <h2 class="text-3xl font-bold text-gray-800">
                Perbaikan Data Pendaftaran
            </h2>
            <p class="text-lg text-gray-600 mt-1">
                {{-- Data ini ambil dari Backend --}}
                Periode Aktif: Agustus - November 2025
            </p>
            <p class="text-sm text-red-600 mt-4 bg-red-50 p-3 rounded-lg">
                <strong>Perhatian:</strong> Pastikan semua data diisi dengan benar. Dokumen wajib dalam format PDF (Maks. 5MB).
            </p>
        </div>

        <hr class="my-6 border-gray-200">

        <form method="POST" action="{{ route('pendaftaran.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <fieldset class="mb-8">
                <legend class="text-xl font-semibold text-gray-700 mb-4">Data Pribadi</legend>

                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        {{-- Mengisi value dengan data lama, atau data dari database --}}
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $pendaftar->nama_lengkap) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        {{-- tempat untuk validasi inline --}}
                        @error('nama') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        {{-- value="{{ Auth::user()->email }}" akan mengisi email user yang login --}}
                        {{-- 'readonly' & 'bg-gray-100' agar tidak bisa diubah --}}
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm bg-gray-100" readonly>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700">NIK (KTP)</label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik', $pendaftar->nik) }}"
                                   class="mt-1 block w-full p-3 border rounded-lg shadow-sm" placeholder="Contoh: 3524..." required>
                            @error('nik') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="wa" class="block text-sm font-medium text-gray-700">No. WhatsApp Aktif</label>
                            <input type="tel" id="wa" name="wa" value="{{ old('wa', $pendaftar->wa) }}"
                                   class="mt-1 block w-full p-3 border rounded-lg shadow-sm" placeholder="Contoh: 0812..." required>
                            @error('wa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat Domisili</label>
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full p-3 border rounded-lg shadow-sm" required>{{ old('address', $pendaftar->address) }}</textarea>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-8">
                <legend class="text-xl font-semibold text-gray-700 mb-4">Data Akademik</legend>
                <div class="space-y-4">
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $pendaftar->nim) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm" required>
                        @error('nim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="universitas" class="block text-sm font-medium text-gray-700">Asal Universitas</label>
                        <input type="text" id="universitas" name="universitas" value="{{ old('universitas', $pendaftar->universitas) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm" placeholder="Contoh: Universitas Airlangga" required>
                        @error('universitas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="jurusan" class="block text-sm font-medium text-gray-700">Fakultas / Jurusan</label>
                        <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $pendaftar->jurusan) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm" placeholder="Contoh: Sains & Teknologi / Statistika" required>
                        @error('jurusan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-8">
                <legend class="text-xl font-semibold text-gray-700 mb-4">Periode Magang</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tgl_start" class="block text-sm font-medium text-gray-700">Tanggal Mulai Magang</label>
                        <input type="date" id="tgl_start" name="tgl_start" value="{{ old('tgl_start', $pendaftar->tgl_start) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm" required>
                        @error('tgl_start') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="tgl_end" class="block text-sm font-medium text-gray-700">Tanggal Selesai Magang</label>
                        <input type="date" id="tgl_end" name="tgl_end" value="{{ old('tgl_end', $pendaftar->tgl_end) }}"
                               class="mt-1 block w-full p-3 border rounded-lg shadow-sm" required>
                        @error('tgl_end') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-8">
                <legend class="text-xl font-semibold text-gray-700 mb-4">Upload Ulang Dokumen (Opsional)</legend>
                <p class="text-sm text-gray-500 mb-4 -mt-2">Kosongkan jika tidak ingin mengganti file yang sudah ada.</p>
                <div class="space-y-4">
                    <div>
                        <label for="rekom" class="block text-sm font-medium text-gray-700">Ganti Surat Rekomendasi</label>
                        @if($pendaftar->rekom)
                            <p class="text-xs text-green-600 mt-1">File saat ini: <a href="{{ route('pendaftaran.preview', ['jenis' => 'rekom']) }}" target="_blank" class="underline">Lihat File</a>
                        @endif
                        <input type="file" id="rekom" name="rekom" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('rekom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="proposal" class="block text-sm font-medium text-gray-700">Ganti Proposal</label>
                        @if($pendaftar->proposal)
                            <p class="text-xs text-green-600 mt-1">File saat ini: <a href="{{ route('pendaftaran.preview', ['jenis' => 'proposal']) }}" target="_blank" class="underline">Lihat File</a>
                        @endif
                        <input type="file" id="proposal" name="proposal"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('proposal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="mb-4">
                    <div class="flex items-start">
                        <input id="konfirmasi" name="konfirmasi" type="checkbox"
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded mt-1" required>
                        <div class="ml-3 text-sm">
                            <label for="konfirmasi" class="font-medium text-gray-700">
                                Saya menyatakan bahwa data yang diisi benar dan dokumen yang diunggah valid.
                            </label>
                        </div>
                    </div>
                    @error('konfirmasi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Kirim Perbaikan
                    </button>
                </div>
            </fieldset>

        </form>

    </div>
</div>
@endsection
