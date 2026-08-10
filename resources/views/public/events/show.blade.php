<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('public.events') }}" class="hover:text-white transition-colors">Kegiatan</a>
                <span>/</span>
                <span class="text-white font-semibold truncate max-w-[200px]">{{ $event->title }}</span>
            </nav>
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-emerald-500 text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider">
                    {{ $event->location }}
                </span>
                <span class="text-xs text-blue-200">
                    {{ $event->starts_at->translatedFormat('d F Y H:i') }}
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $event->title }}</h1>
        </div>
    </section>

    {{-- Event content --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Main detail content --}}
            <article class="lg:col-span-8 bg-white border border-slate-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                @if($event->cover_image)
                    <div class="mb-6 rounded-xl overflow-hidden aspect-video max-h-96 w-full shadow-sm">
                        <img src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}" class="object-cover w-full h-full">
                    </div>
                @endif

                <div class="prose max-w-none text-slate-600">
                    {!! $event->description !!}
                </div>
            </article>

            {{-- Info Sidebar --}}
            <aside class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-6" aria-label="Detail waktu dan lokasi">
                <div>
                    <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Detail Agenda</h3>
                    <div class="space-y-4 text-sm text-slate-600">
                        <div class="flex items-start gap-3">
                            <div>
                                <div class="font-bold text-slate-700">Tanggal</div>
                                <div>{{ $event->starts_at->translatedFormat('d F Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-lg">⏰</span>
                            <div>
                                <div class="font-bold text-slate-700">Waktu</div>
                                <div>
                                    {{ $event->starts_at->format('H:i') }} -
                                    {{ $event->ends_at ? $event->ends_at->format('H:i') . ' WIT' : 'Selesai' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div>
                                <div class="font-bold text-slate-700">Lokasi</div>
                                <div>{{ $event->location ?: 'Ruang IT / RSUD' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-public-layout>
