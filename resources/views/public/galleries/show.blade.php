@php
    $lightboxItems = $gallery->items->map(fn($item) => [
        'src' => asset('storage/'.$item->file_path),
        'alt' => $item->alt_text ?: $item->caption ?: 'Dokumentasi '.$gallery->title,
        'caption' => $item->caption,
    ])->values();
@endphp

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
                    <button
                        type="button"
                        class="block aspect-video overflow-hidden group w-full text-left"
                        data-gallery-open
                        data-gallery-index="{{ $loop->index }}"
                        aria-label="Lihat foto {{ $loop->iteration }}"
                    >
                        <img
                            src="{{ asset('storage/'.$item->file_path) }}"
                            alt="{{ $item->alt_text ?: $item->caption ?: 'Dokumentasi ' . $gallery->title }}"
                            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                        >
                    </button>
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

    @if($lightboxItems->isNotEmpty())
        <div id="gallery-lightbox" class="fixed inset-0 z-[200] hidden bg-slate-950/90 p-4 sm:p-6" role="dialog" aria-modal="true" aria-label="Pratinjau foto galeri">
            <button type="button" class="absolute inset-0 cursor-default" data-gallery-close aria-label="Tutup pratinjau"></button>

            <div class="relative z-10 mx-auto flex h-full max-w-6xl flex-col">
                <div class="flex items-center justify-between gap-4 pb-4 text-white">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-200">Galeri Foto</p>
                        <h2 class="truncate text-lg font-bold">{{ $gallery->title }}</h2>
                    </div>
                    <button type="button" data-gallery-close class="btn btn-sm bg-white/10 text-white hover:bg-white/20 border border-white/20">
                        Tutup
                    </button>
                </div>

                <div class="relative flex min-h-0 flex-1 items-center justify-center">
                    <img id="gallery-lightbox-image" src="" alt="" class="max-h-full max-w-full rounded-xl object-contain shadow-2xl">

                    @if($lightboxItems->count() > 1)
                        <button type="button" data-gallery-prev class="absolute left-0 sm:left-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20" aria-label="Foto sebelumnya">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" data-gallery-next class="absolute right-0 sm:right-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white border border-white/20 hover:bg-white/20" aria-label="Foto berikutnya">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </div>

                <div class="pt-4 text-center text-white">
                    <p id="gallery-lightbox-caption" class="mx-auto max-w-3xl text-sm text-white/80"></p>
                    <p id="gallery-lightbox-counter" class="mt-2 text-xs font-semibold text-blue-200"></p>
                </div>
            </div>
        </div>

        <script>
            (() => {
                const items = @js($lightboxItems);
                const modal = document.getElementById('gallery-lightbox');
                const image = document.getElementById('gallery-lightbox-image');
                const caption = document.getElementById('gallery-lightbox-caption');
                const counter = document.getElementById('gallery-lightbox-counter');
                let activeIndex = 0;

                if (!modal || !image || !items.length) return;

                const render = () => {
                    const item = items[activeIndex];
                    image.src = item.src;
                    image.alt = item.alt;
                    caption.textContent = item.caption || item.alt || '';
                    counter.textContent = `${activeIndex + 1} / ${items.length}`;
                };

                const open = (index) => {
                    activeIndex = index;
                    render();
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                };

                const close = () => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    image.src = '';
                };

                const move = (step) => {
                    activeIndex = (activeIndex + step + items.length) % items.length;
                    render();
                };

                document.querySelectorAll('[data-gallery-open]').forEach((button) => {
                    button.addEventListener('click', () => open(Number(button.dataset.galleryIndex || 0)));
                });

                modal.querySelectorAll('[data-gallery-close]').forEach((button) => {
                    button.addEventListener('click', close);
                });

                modal.querySelector('[data-gallery-prev]')?.addEventListener('click', () => move(-1));
                modal.querySelector('[data-gallery-next]')?.addEventListener('click', () => move(1));

                document.addEventListener('keydown', (event) => {
                    if (modal.classList.contains('hidden')) return;
                    if (event.key === 'Escape') close();
                    if (event.key === 'ArrowLeft' && items.length > 1) move(-1);
                    if (event.key === 'ArrowRight' && items.length > 1) move(1);
                });
            })();
        </script>
    @endif
</x-public-layout>
