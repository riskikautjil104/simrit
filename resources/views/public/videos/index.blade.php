<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header video">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Video</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Galeri Video Kegiatan</h1>
        </div>
    </section>

    {{-- Videos grid --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($videos as $vid)
                <div class="card overflow-hidden bg-white border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="aspect-video w-full bg-slate-900 relative">
                            {{-- Embed player based on URL --}}
                            @if($vid->embed_url)
                                @if(Str::contains($vid->embed_url, ['youtube.com', 'youtu.be']))
                                    @php
                                        // Simple youtube ID extractor
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $vid->embed_url, $match);
                                        $ytId = $match[1] ?? null;
                                    @endphp
                                    @if($ytId)
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $ytId }}"
                                            class="w-full h-full"
                                            title="{{ $vid->title }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold">Invalid YouTube Embed</div>
                                    @endif
                                @elseif(Str::contains($vid->embed_url, 'vimeo.com'))
                                    @php
                                        preg_match('%vimeo\.com/(?:video/)?([0-9]+)%i', $vid->embed_url, $match);
                                        $vimeoId = $match[1] ?? null;
                                    @endphp
                                    @if($vimeoId)
                                        <iframe
                                            src="https://player.vimeo.com/video/{{ $vimeoId }}"
                                            class="w-full h-full"
                                            title="{{ $vid->title }}"
                                            frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold">Invalid Vimeo Embed</div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold">Format video tidak didukung</div>
                                @endif
                            @elseif($vid->file_path)
                                <video src="{{ asset('storage/'.$vid->file_path) }}" controls class="w-full h-full"></video>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold">Pemutar Video</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <span class="text-[11px] text-slate-400 font-medium">
                                {{ $vid->published_at ? $vid->published_at->format('d M Y') : $vid->created_at->format('d M Y') }}
                            </span>
                            <h2 class="text-base font-bold text-slate-800 leading-snug mt-1">{{ $vid->title }}</h2>
                            <p class="text-slate-500 text-xs leading-relaxed mt-2">
                                {{ $vid->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <h3 class="mt-2">Belum ada video kegiatan</h3>
                    <p>Galeri publik untuk video kegiatan IT belum ditambahkan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $videos->links() }}
        </div>
    </section>
</x-public-layout>
