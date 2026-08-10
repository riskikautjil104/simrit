<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header agenda">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Kegiatan</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Agenda Kegiatan IT</h1>
        </div>
    </section>

    {{-- Main Events List Section --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Search input --}}
        <form method="GET" action="{{ route('public.events') }}" class="mb-10 max-w-xl">
            <div class="relative">
                <label for="search-event" class="sr-only">Cari kegiatan...</label>
                <input
                    type="text"
                    id="search-event"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-input w-full pl-10"
                    placeholder="Cari agenda kegiatan IT..."
                >
            </div>
        </form>

        {{-- Events list/grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($events as $ev)
                <article class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl bg-blue-50 flex-shrink-0 flex flex-col items-center justify-center text-[#1d4ed8] border border-blue-100 font-bold">
                                <span class="text-2xl font-black leading-none">{{ $ev->starts_at->format('d') }}</span>
                                <span class="text-[10px] uppercase tracking-wider mt-1">{{ $ev->starts_at->format('M') }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-bold uppercase tracking-wider">
                                        {{ $ev->location }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">
                                        {{ $ev->starts_at->format('H:i') }} - {{ $ev->ends_at ? $ev->ends_at->format('H:i') : 'Selesai' }}
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-800 leading-snug hover:text-[#1d4ed8] transition-colors">
                                    <a href="{{ route('public.events.show', $ev->slug) }}">{{ $ev->title }}</a>
                                </h2>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs leading-relaxed line-clamp-3">
                            {{ strip_tags($ev->description) }}
                        </p>
                    </div>
                    <div class="border-t border-slate-100 pt-4 mt-5 flex justify-end">
                        <a href="{{ route('public.events.show', $ev->slug) }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold inline-flex items-center gap-1">
                            Detail Agenda
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3>Tidak ditemukan kegiatan</h3>
                    <p>Silakan coba cari dengan kata kunci lain.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </section>
</x-public-layout>
