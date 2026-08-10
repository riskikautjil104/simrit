<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header layanan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Layanan IT</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Katalog Layanan Teknologi Informasi</h1>
        </div>
    </section>

    {{-- Services catalog --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $svc)
                <div class="card p-6 bg-white border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-[#1d4ed8] text-xl font-bold mb-5 border border-blue-100">
                            @if($svc->icon)
                                {!! $svc->icon !!}
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 3.75h4.5A1.75 1.75 0 0116 5.5v13A1.75 1.75 0 0114.25 20h-4.5A1.75 1.75 0 018 18.5v-13a1.75 1.75 0 011.75-1.75zM11 17h2M10.5 6h3"/></svg>
                            @endif
                        </div>
                        <h2 class="text-lg font-bold text-slate-800 mb-2">{{ $svc->title }}</h2>
                        <p class="text-slate-500 text-xs leading-relaxed mb-6">
                            {{ $svc->short_description }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('public.services.show', $svc->slug) }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold inline-flex items-center gap-1">
                            Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="mt-2">Belum ada katalog layanan IT</h3>
                    <p>Katalog resmi untuk layanan IT RSUD belum diinput.</p>
                </div>
            @endforelse
        </div>
    </section>
</x-public-layout>
