@extends('layouts.admin')

@section('header', 'Audit Data Presensi')

@section('content')

<div class="bg-white rounded-lg shadow-sm p-6">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.presensi.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">

            <select name="jenis" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Status</option>
                <option value="datang" {{ request('jenis') == 'datang' ? 'selected' : '' }}>Hadir (Datang)</option>
                <option value="pulang" {{ request('jenis') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Mahasiswa..." class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">

            <button type="submit" class="bg-blue-600 text-white font-medium rounded-lg text-sm px-4 py-2 hover:bg-blue-700">
                Filter
            </button>

            <a href="{{ route('admin.presensi.export', request()->query()) }}" class="bg-green-600 text-white font-medium rounded-lg text-sm px-4 py-2 hover:bg-green-700 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh Excel
            </a>
            
            @if(request()->hasAny(['tanggal', 'jenis', 'search']))
                <a href="{{ route('admin.presensi.index') }}" class="text-gray-500 text-sm underline ml-2">Reset</a>
            @endif
        </form>
    </div>

    <div class="relative overflow-x-auto border rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Waktu</th>
                    <th class="px-6 py-3">Nama Mahasiswa</th>
                    <th class="px-6 py-3 text-center">Jenis</th>
                    <th class="px-6 py-3">Lokasi</th>
                    <th class="px-6 py-3">Keterangan / Bukti</th>
                    <th class="px-6 py-3 text-center">Status Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    @php
                        $isLate = ($item->jenis == 'datang' && $item->jam > '07:30:00');
                        $rowClass = $isLate ? 'bg-yellow-50 border-l-4 border-yellow-400' : 'bg-white border-b hover:bg-gray-50';
                    @endphp

                <tr class="{{ $rowClass }}">
                    
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</div>
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $item->pendaftar->nama_lengkap }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($item->jenis == 'datang')
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">DATANG</span>
                        @elseif($item->jenis == 'pulang')
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded">PULANG</span>
                        @elseif($item->jenis == 'sakit')
                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded">SAKIT</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded">IZIN</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                       <button type="button" 
                                @click="$dispatch('open-map', { url: 'https://maps.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}&hl=id&z=15&output=embed' })"
                                class="text-blue-600 hover:text-blue-800 flex items-center text-xs font-medium">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Lihat Peta
                        </button>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            
                            @if($item->bukti_file)
                                <a href="{{ route('admin.presensi.download', $item->id) }}" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded flex items-center w-max transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh Bukti
                                </a>
                            @endif

                            @if($item->keterangan)
                                <div class="group relative w-max cursor-help">
                                    <span class="text-xs text-gray-500 border-b border-dotted border-gray-400">Lihat Keterangan</span>
                                    <div class="absolute bottom-full left-0 mb-2 w-48 p-2 bg-gray-800 text-white text-xs rounded hidden group-hover:block z-10 shadow-lg">
                                        {{ $item->keterangan }}
                                    </div>
                                </div>
                            @endif

                            @if(!$item->bukti_file && !$item->keterangan)
                                <span class="text-xs text-gray-300">-</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($isLate)
                            <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">TERLAMBAT</span>
                        @elseif($item->jenis == 'datang')
                            <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded">TEPAT WAKTU</span>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data presensi yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>

</div>

<!-- Modal Map Lokasi Presensi -->
<div x-data="{ showMap: false, mapUrl: '' }" 
     @open-map.window="showMap = true; mapUrl = $event.detail.url" 
     x-show="showMap" 
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showMap" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showMap = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showMap" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Lokasi Presensi</h3>
                <div class="aspect-w-16 aspect-h-9 h-64 bg-gray-100 rounded-lg overflow-hidden">
                    <iframe :src="mapUrl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="showMap = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection