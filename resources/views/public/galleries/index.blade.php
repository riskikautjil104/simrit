<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header galeri">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Galeri</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Galeri Kegiatan IT</h1>
        </div>
    </section>

    {{-- Galleries list --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($galleries as $gal)
                <article class="card overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="aspect-video w-full bg-slate-100 relative overflow-hidden">
                            @if($gal->cover_image)
                                <img src="{{ asset('storage/'.$gal->cover_image) }}" alt="{{ $gal->title }}" class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold bg-[#eff6ff]">GALLERY</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <span class="text-[11px] text-slate-400 font-medium">
                                {{ $gal->published_at ? $gal->published_at->format('d M Y') : $gal->created_at->format('d M Y') }}
                            </span>
                            <h2 class="text-base font-bold text-slate-800 leading-snug mt-1 hover:text-[#1d4ed8] transition-colors">
                                <a href="{{ route('public.galleries.show', $gal->slug) }}">{{ $gal->title }}</a>
                            </h2>
                            <p class="text-slate-500 text-xs leading-relaxed mt-2 line-clamp-2">
                                {{ $gal->description }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-1">
                        <a href="{{ route('public.galleries.show', $gal->slug) }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold inline-flex items-center gap-1">
                            Buka Album
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3>Belum ada galeri foto</h3>
                    <p>Album dokumentasi foto kegiatan IT belum diunggah.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $galleries->links() }}
        </div>
    </section>
</x-public-layout>
