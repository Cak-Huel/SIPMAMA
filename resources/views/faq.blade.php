@extends('layouts.main')

@section('content')

<div class="bg-blue-600 py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Sering Ditanyakan</h1>
        <p class="text-blue-100 text-lg">Temukan jawaban untuk pertanyaan umum seputar magang.</p>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 max-w-4xl">

    @if($faqs->count() > 0)
        <div class="space-y-4">
            @foreach($faqs as $item)
                <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                    <button @click="open = !open" class="w-full text-left px-6 py-4 focus:outline-none bg-gray-50 hover:bg-gray-100 transition-colors flex justify-between items-center">
                        <span class="font-semibold text-gray-800 text-lg">{{ $item->pertanyaan }}</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-6 py-4 bg-white text-gray-600 leading-relaxed border-t border-gray-100">
                        {!! nl2br(e($item->jawaban)) !!}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            Belum ada data FAQ.
        </div>
    @endif

</div>
@endsection