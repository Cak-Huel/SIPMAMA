@extends('layouts.main')

@section('content')

<div class="bg-blue-600 py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Galeri Kegiatan</h1>
        <p class="text-blue-100 text-lg">Dokumentasi aktivitas dan keseruan magang di BPS Lamongan.</p>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    @if($galeri->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($galeri as $item)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                    <div class="h-64 overflow-hidden relative group">
                        <img src="{{ Storage::url($item->foto_path) }}" 
                             alt="{{ $item->judul }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        
                        <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            {{ $item->periode }}
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->judul }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                            {{ $item->deskripsi ?? '-' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $galeri->links() }}
        </div>

    @else
        <div class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <h3 class="text-xl font-medium text-gray-600">Belum ada foto galeri.</h3>
            <p class="text-gray-500">Nantikan update kegiatan kami segera!</p>
        </div>
    @endif

</div>

@endsection