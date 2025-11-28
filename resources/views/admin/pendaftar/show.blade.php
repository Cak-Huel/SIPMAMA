@extends('layouts.admin')

@section('header', 'Verifikasi Pendaftar')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <div class="lg:col-span-3 space-y-6">
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center mb-6">
                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-blue-600">{{ substr($pendaftar->nama_lengkap, 0, 1) }}</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}</h3>
                <p class="text-sm text-gray-500">{{ $pendaftar->nim }}</p>
            </div>

            <hr class="border-gray-100 my-4">

            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Universitas</label>
                    <p class="text-sm font-medium text-gray-800">{{ $pendaftar->universitas }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Jurusan</label>
                    <p class="text-sm font-medium text-gray-800">{{ $pendaftar->jurusan }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Kontak (WA)</label>
                    <p class="text-sm font-medium text-gray-800">{{ $pendaftar->wa }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Periode Magang</label>
                    <p class="text-sm font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($pendaftar->tgl_start)->format('d M Y') }} - <br>
                        {{ \Carbon\Carbon::parse($pendaftar->tgl_end)->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.pendaftar.index') }}" class="block text-center w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
            &larr; Kembali ke Tabel
        </a>
    </div>


    <div class="lg:col-span-6" x-data="{ tab: 'proposal' }">
        <div class="bg-white rounded-lg shadow-sm h-[800px] flex flex-col">
            
            <div class="flex border-b">
                <button @click="tab = 'proposal'" 
                        :class="{ 'border-b-2 border-blue-500 text-blue-600': tab === 'proposal', 'text-gray-500 hover:text-gray-700': tab !== 'proposal' }"
                        class="flex-1 py-3 px-4 text-sm font-medium text-center transition-colors">
                    📄 Proposal Magang
                </button>
                @if($pendaftar->rekom)
                <button @click="tab = 'rekom'" 
                        :class="{ 'border-b-2 border-blue-500 text-blue-600': tab === 'rekom', 'text-gray-500 hover:text-gray-700': tab !== 'rekom' }"
                        class="flex-1 py-3 px-4 text-sm font-medium text-center transition-colors">
                    📜 Surat Rekomendasi
                </button>
                @endif
            </div>

            <div class="flex-1 bg-gray-100 p-4">
                
                <div x-show="tab === 'proposal'" class="h-full flex flex-col">
                    <iframe src="{{ route('admin.pendaftar.preview', ['id' => $pendaftar->id_pendaftar, 'jenis' => 'proposal']) }}" class="w-full flex-1 rounded border shadow-sm"></iframe>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.pendaftar.download', ['id' => $pendaftar->id_pendaftar, 'jenis' => 'proposal']) }}" class="text-sm text-blue-600 hover:underline">
                            Unduh Proposal (.pdf)
                        </a>
                    </div>
                </div>

                @if($pendaftar->rekom)
                <div x-show="tab === 'rekom'" class="h-full flex flex-col" style="display: none;">
                    <iframe src="{{ route('admin.pendaftar.preview', ['id' => $pendaftar->id_pendaftar, 'jenis' => 'rekom']) }}" class="w-full flex-1 rounded border shadow-sm"></iframe>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.pendaftar.download', ['id' => $pendaftar->id_pendaftar, 'jenis' => 'rekom']) }}" class="text-sm text-blue-600 hover:underline">
                            Unduh Surat Rekomendasi (.pdf)
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>


    <div class="lg:col-span-3 space-y-6">
        
        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Keputusan Verifikasi</h3>

            <div class="mb-6 text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Status Saat Ini</p>
                @if($pendaftar->status == 'menunggu')
                    <span class="text-xl font-bold text-yellow-600">PENDING</span>
                @elseif($pendaftar->status == 'lolos')
                    <span class="text-xl font-bold text-green-600">LOLOS</span>
                @elseif($pendaftar->status == 'ditolak')
                    <span class="text-xl font-bold text-red-600">DITOLAK</span>
                @endif
            </div>

            @if($pendaftar->status == 'menunggu')
                
                <form method="POST" action="{{ route('admin.pendaftar.update', $pendaftar->id_pendaftar) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Verifikasi <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 border" placeholder="Tulis alasan..." required></textarea>
                        @error('catatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <button type="submit" name="status" value="lolos" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-colors flex justify-center items-center" onclick="return confirm('Yakin ingin meloloskan?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Loloskan Pendaftar
                        </button>

                        <button type="submit" name="status" value="ditolak" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-colors flex justify-center items-center" onclick="return confirm('Yakin ingin menolak?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak Pendaftar
                        </button>
                    </div>

                @error('status')
                    <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p>
                @enderror
            </form>

            @else
                <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-600 font-medium">Pendaftar ini sudah diverifikasi.</p>
                    <p class="text-xs text-gray-500 mt-1">Keputusan bersifat final.</p>
                    
                    <div class="mt-4 text-left bg-white p-3 rounded border text-sm">
                        <span class="font-bold text-xs text-gray-400 uppercase">Catatan Admin:</span>
                        <p class="text-gray-800 mt-1">{{ $pendaftar->catatan }}</p>
                    </div>
                </div>
            @endif

            @if($pendaftar->verified_by)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Riwayat Verifikasi</h4>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-xs font-bold text-gray-600">A</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Admin #{{ $pendaftar->verified_by }}</p>
                        <p class="text-xs text-gray-500">
                            Memperbarui status menjadi 
                            <span class="font-semibold text-gray-700">{{ strtoupper($pendaftar->status) }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $pendaftar->verified_at ? \Carbon\Carbon::parse($pendaftar->verified_at)->diffForHumans() : '-' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

@endsection