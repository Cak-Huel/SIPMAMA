{{-- layout induk (otomatis dapat Navbar & Footer) --}}
@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Profil Saya</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700">Data Akun</h2>
                <div class="mt-4 space-y-2">
                    <div>
                        <span class="font-medium text-gray-500">Nama:</span>
                        {{-- Data $user ini didapat dari ProfileController --}}
                        <span class="text-gray-800">{{ $user->nama }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-500">Email:</span>
                        <span class="text-gray-800">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Status Pendaftaran</h2>
                
                {{-- Cek apakah user punya data pendaftaran ($pendaftar didapat dari Controller) --}}
                @if ($pendaftar)
                    
                    <div class="mb-6">
                        @if ($pendaftar->status == 'menunggu')
                            <span class="text-base font-semibold py-2 px-4 rounded-lg bg-yellow-100 text-yellow-800">
                                🟡 MENUNGGU VERIFIKASI
                            </span>
                        @elseif ($pendaftar->status == 'lolos')
                            <span class="text-base font-semibold py-2 px-4 rounded-lg bg-green-100 text-green-800">
                                ✅ LOLOS
                            </span>
                            <p class="text-gray-600 mt-2">Selamat! Anda diterima untuk periode 
                                {{ \Carbon\Carbon::parse($pendaftar->tgl_start)->format('d M Y') }} 
                                s/d 
                                {{ \Carbon\Carbon::parse($pendaftar->tgl_end)->format('d M Y') }}.
                            </p>
                        @elseif ($pendaftar->status == 'ditolak')
                            <span class="text-base font-semibold py-2 px-4 rounded-lg bg-red-100 text-red-800">
                                ❌ DITOLAK
                            </span>
                            <p class="text-gray-600 mt-2">Maaf, pendaftaran Anda ditolak. Anda dapat mendaftar kembali untuk periode selanjutnya.</p>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold text-gray-600 mb-3">Status Dokumen</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium">Proposal Magang</span>
                            @if ($pendaftar->proposal)
                                <span class="text-sm font-medium text-green-600">✔ Terupload</span>
                            @else
                                <span class="text-sm font-medium text-red-600">✖ Belum Upload</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium">Surat Rekomendasi</span>
                            @if ($pendaftar->rekom)
                                <span class="text-sm font-medium text-green-600">✔ Terupload</span>
                            @else
                                <form method="POST" action="{{ route('profil.rekom.upload') }}" enctype="multipart/form-data" 
                                    class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                    @csrf
                                    
                                    <input type="file" name="rekom" required
                                        class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100">
                                    
                                    <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white py-2 px-4 rounded-lg 
                                    hover:bg-blue-700 text-sm font-medium transition-colors">
                                        Upload
                                    </button>
                                </form>
                            @endif
                        </div>
                        @error('rekom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                @else
                    {{-- Jika user belum pernah mendaftar --}}
                    <p class="text-gray-600">Anda belum melakukan pendaftaran magang.</p>
                    <a href="{{ route('pendaftaran.form') }}" class="inline-block mt-4 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                        Daftar Magang Sekarang
                    </a>
                @endif
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Aksi Akun</h2>
                <div class="flex space-x-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 font-medium">
                            Logout
                        </button>
                    </form>

                    <form method="POST" action="{{ route('profil.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini akan meminta konfirmasi password dan tidak dapat dibatalkan.');">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 font-medium">
                            Hapus Akun
                        </button>
                    </form>
                </div>
                <p class="text-sm text-gray-500 mt-3">
                    <strong>Penting:</strong> Menghapus akun bersifat permanen. Riwayat pendaftaran Anda akan dianonimkan tetapi tetap tersimpan untuk audit.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection