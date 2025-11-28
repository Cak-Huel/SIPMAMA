@extends('layouts.admin')

@section('header', 'Kelola Pendaftar')

@section('content')

<div class="bg-white rounded-lg shadow-sm p-6">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        
        <form method="GET" action="{{ route('admin.pendaftar.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            
            <select name="status" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="lolos" {{ request('status') == 'lolos' ? 'selected' : '' }}>Lolos</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <select name="bulan" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari Nama/Kampus...">
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                Filter
            </button>
            
            @if(request()->hasAny(['status', 'bulan', 'search']))
                <a href="{{ route('admin.pendaftar.index') }}" class="text-gray-500 hover:text-gray-700 text-sm underline ml-2">Reset</a>
            @endif
        </form>

        <div class="flex gap-2">
            <a href="{{ route('admin.pendaftar.export', request()->query()) }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Excel/CSV
            </a>
        </div>
    </div>

    <div class="relative overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Nama Mahasiswa</th>
                    <th scope="col" class="px-6 py-3">Asal Kampus</th>
                    <th scope="col" class="px-6 py-3">Periode</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendaftar as $key => $item)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $pendaftar->firstItem() + $key }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $item->nama_lengkap }}
                        @if($item->source == 'admin_manual')
                             <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded border border-purple-400 ml-2">Manual</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->universitas }}<br>
                        <span class="text-xs text-gray-400">{{ $item->jurusan }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($item->tgl_start)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($item->tgl_end)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status == 'menunggu')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300">Pending</span>
                        @elseif($item->status == 'lolos')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Lolos</span>
                        @elseif($item->status == 'ditolak')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            
                            {{-- Nanti href-nya kita ganti ke route verifikasi --}}
                            <a href="{{ route('admin.pendaftar.show', $item->id_pendaftar) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">
                                Detail / Verifikasi
                            </a>

                            <a href="{{ route('admin.pendaftar.download', ['id' => $item->id_pendaftar, 'jenis' => 'proposal']) }}" class="text-gray-500 hover:text-blue-600" title="Download Proposal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>

                            @if($item->rekom)
                            <a href="{{ route('admin.pendaftar.download', ['id' => $item->id_pendaftar, 'jenis' => 'rekom']) }}" class="text-gray-500 hover:text-green-600" title="Download Surat Rekom">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Belum ada data pendaftar yang sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pendaftar->links() }}
    </div>

</div>

@endsection