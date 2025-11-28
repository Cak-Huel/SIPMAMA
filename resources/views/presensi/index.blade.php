@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Presensi Harian</h2>

        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm border-b pb-4">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-bold text-gray-800">{{ $user->nama }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu Server</p>
                    <p class="font-bold text-blue-600">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-gray-500">Lokasi Anda</p>
                    <p id="lokasi-status" class="font-bold text-yellow-600 animate-pulse">Mendeteksi...</p>
                </div>
            </div>

            <form method="POST" action="{{ route('presensi.store') }}" enctype="multipart/form-data" id="form-presensi">
                @csrf
                
                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="long">

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Status Presensi</label>
                    <div class="grid grid-cols-2 gap-3" x-data="{ selected: '' }">
                        
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="datang" class="peer sr-only" onchange="toggleInput('datang')">
                            <div class="p-3 text-center border rounded-lg hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white transition-all">
                                🏢 Datang
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="pulang" class="peer sr-only" onchange="toggleInput('pulang')">
                            <div class="p-3 text-center border rounded-lg hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white transition-all">
                                🏠 Pulang
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="izin" class="peer sr-only" onchange="toggleInput('izin')">
                            <div class="p-3 text-center border rounded-lg hover:bg-yellow-50 peer-checked:bg-yellow-500 peer-checked:text-white transition-all">
                                📝 Izin
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="jenis" value="sakit" class="peer sr-only" onchange="toggleInput('sakit')">
                            <div class="p-3 text-center border rounded-lg hover:bg-red-50 peer-checked:bg-red-500 peer-checked:text-white transition-all">
                                🏥 Sakit
                            </div>
                        </label>
                    </div>
                    @error('jenis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div id="extra-input" class="hidden space-y-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    
                    <div id="box-keterangan" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Keterangan / Alasan</label>
                        <textarea name="keterangan" rows="2" class="w-full mt-1 border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2"></textarea>
                        <p class="text-xs text-gray-500 mt-1" id="hint-keterangan"></p>
                    </div>

                    <div id="box-file" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Upload Dokumen Pendukung (PDF/Foto)</label>
                        <input type="file" name="bukti_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-red-500 mt-1 hidden" id="hint-file-wajib">* Wajib diisi untuk status Sakit</p>
                    </div>
                </div>

                <button type="submit" id="btn-submit" disabled class="w-full bg-gray-400 text-white font-bold py-3 rounded-lg cursor-not-allowed transition-colors">
                    Kirim Presensi
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Terbaru</h3>
            <ul class="space-y-3">
                @forelse($riwayat as $log)
                    <li class="flex justify-between items-center border-b pb-2 last:border-0">
                        <div>
                            <span class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M') }}</span>
                            <span class="text-xs text-gray-500 ml-2">{{ \Carbon\Carbon::parse($log->jam)->format('H:i') }}</span>
                            <div class="text-xs text-gray-400">Lat: {{ substr($log->latitude,0,6) }}...</div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $log->jenis == 'datang' ? 'bg-blue-100 text-blue-700' : 
                                  ($log->jenis == 'pulang' ? 'bg-gray-100 text-gray-700' : 
                                  ($log->jenis == 'sakit' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $log->jenis }}
                            </span>
                        </div>
                    </li>
                @empty
                    <p class="text-gray-500 text-sm text-center">Belum ada riwayat presensi.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    // 1. GEOLOCATION SCRIPT
    document.addEventListener("DOMContentLoaded", function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            document.getElementById("lokasi-status").innerHTML = "Geolocation tidak didukung browser ini.";
        }
    });

    function showPosition(position) {
        // Isi nilai hidden input
        document.getElementById("lat").value = position.coords.latitude;
        document.getElementById("long").value = position.coords.longitude;
        
        // Update status UI
        const status = document.getElementById("lokasi-status");
        status.innerHTML = "Lokasi Terkunci ✅";
        status.className = "font-bold text-green-600";
        
        // Aktifkan tombol submit
        const btn = document.getElementById("btn-submit");
        btn.disabled = false;
        btn.classList.remove("bg-gray-400", "cursor-not-allowed");
        btn.classList.add("bg-blue-600", "hover:bg-blue-700");
    }

    function showError(error) {
        const status = document.getElementById("lokasi-status");
        status.innerHTML = "Gagal Deteksi Lokasi (Aktifkan GPS)";
        status.className = "font-bold text-red-600";
    }

    // 2. FORM DYNAMIC SCRIPT
    function toggleInput(jenis) {
        const boxExtra = document.getElementById('extra-input');
        const boxKet = document.getElementById('box-keterangan');
        const boxFile = document.getElementById('box-file');
        const hintFile = document.getElementById('hint-file-wajib');
        const hintKet = document.getElementById('hint-keterangan');

        // Default: Sembunyikan semua extra
        boxExtra.classList.add('hidden');
        boxKet.classList.add('hidden');
        boxFile.classList.add('hidden');
        hintFile.classList.add('hidden');

        if (jenis === 'izin') {
            boxExtra.classList.remove('hidden');
            boxKet.classList.remove('hidden');
            boxFile.classList.remove('hidden'); // Izin BOLEH upload file
            hintKet.innerHTML = "* Isi ini ATAU upload dokumen.";
        } 
        else if (jenis === 'sakit') {
            boxExtra.classList.remove('hidden');
            boxFile.classList.remove('hidden');
            hintFile.classList.remove('hidden'); // Munculkan teks WAJIB
        }
    }
</script>
@endsection