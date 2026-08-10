<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header tim">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Tim IT</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Struktur Tim IT & SIMRS</h1>
        </div>
    </section>

    {{-- Team Grid --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($members as $m)
                <div class="card overflow-hidden text-center bg-white border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="aspect-square w-full bg-slate-50 relative overflow-hidden border-b border-slate-50">
                            @if($m->photo)
                                <img src="{{ asset('storage/'.$m->photo) }}" alt="Foto {{ $m->name }}" class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-50/50 text-[#1d4ed8] text-4xl font-extrabold select-none">
                                    {{ strtoupper(substr($m->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h2 class="text-sm font-bold text-slate-800 line-clamp-1">{{ $m->name }}</h2>
                            <div class="text-[11px] font-bold text-[#1d4ed8] uppercase mt-1 tracking-wider line-clamp-1">
                                {{ $m->position }}
                            </div>
                            @if($m->department)
                                <div class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">
                                    {{ $m->department }}
                                </div>
                            @endif
                            @if($m->biography)
                                <p class="text-slate-500 text-[11px] leading-relaxed mt-3 line-clamp-3">
                                    {{ $m->biography }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="mt-2">Belum ada tim yang didaftarkan</h3>
                    <p>Daftar anggota tim IT RSUD belum diunggah.</p>
                </div>
            @endforelse
        </div>
    </section>
</x-public-layout>
