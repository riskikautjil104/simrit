<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('public.galleries') }}" class="hover:text-white transition-colors">Galeri</a>
                <span>/</span>
                <span class="text-white font-semibold truncate max-w-[200px]">{{ $gallery->title }}</span>
            </nav>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs text-blue-200">
                    {{ $gallery->published_at ? $gallery->published_at->format('d M Y') : $gallery->created_at->format('d M Y') }}
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $gallery->title }}</h1>
        </div>
    </section>

    {{-- Main Images grid --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Description --}}
        @if($gallery->description)
            <div class="bg-white border border-slate-100 rounded-2xl p-6 mb-8 text-sm text-slate-600 shadow-sm">
                {{ $gallery->description }}
            </div>
        @endif

        {{-- Photos Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($gallery->items as $item)
                <div class="card overflow-hidden bg-white border border-slate-100">
                    <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" class="block aspect-video overflow-hidden group">
                        <img
                            src="{{ asset('storage/'.$item->file_path) }}"
                            alt="{{ $item->alt_text ?: $item->caption ?: 'Dokumentasi ' . $gallery->title }}"
                            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                        >
                    </a>
                    @if($item->caption)
                        <div class="p-4 border-t border-slate-50">
                            <p class="text-slate-600 text-xs leading-relaxed">{{ $item->caption }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="mt-2">Album foto kosong</h3>
                    <p>Belum ada dokumentasi foto yang diunggah ke dalam album ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</x-public-layout>
