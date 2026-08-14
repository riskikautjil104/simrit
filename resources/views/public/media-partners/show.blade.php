@php
    $partnerDescription = $partner->description ?: 'Profil media partner '.$partner->name.' yang mendukung publikasi informasi SIMRIT RSUD Dr. H. Chasan Boesoirie.';
    $partnerImage = $partner->logo ? asset('storage/'.$partner->logo) : asset('logo/logoruangit.png');
    $partnerSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $partner->name,
        'description' => $partnerDescription,
        'url' => url()->current(),
        'logo' => $partnerImage,
    ];
@endphp

<x-public-layout :title="$partner->name" :meta-description="$partnerDescription" :meta-image="$partnerImage" :schema="$partnerSchema">
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span>Media Partner</span>
                <span>/</span>
                <span class="text-white font-semibold truncate max-w-[220px]">{{ $partner->name }}</span>
            </nav>
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-emerald-500 text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider">
                    Media Partner
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $partner->name }}</h1>
        </div>
    </section>

    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <article class="lg:col-span-8 bg-white border border-slate-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                    <div class="w-full sm:w-48 aspect-video rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($partner->logo)
                            <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" class="w-full h-full object-contain p-4">
                        @else
                            <span class="text-4xl font-black text-[#1d4ed8]">{{ strtoupper(substr($partner->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-[#1d4ed8] uppercase tracking-wider mb-2">Profil Partner</p>
                        <h2 class="text-2xl font-black text-[#1e3a8a] tracking-tight break-words">{{ $partner->name }}</h2>
                        <p class="mt-4 text-slate-600 leading-relaxed">
                            {{ $partner->description ?: 'Media partner ini mendukung publikasi informasi dan kegiatan SIMRIT RSUD Dr. H. Chasan Boesoirie.' }}
                        </p>

                        @if($partner->link)
                            <div class="mt-6">
                                <a href="{{ $partner->link }}" target="_blank" rel="noopener" class="btn btn-primary">
                                    Kunjungi Website
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14m-3-4H5a2 2 0 00-2 2v7a2 2 0 002 2h7a2 2 0 002-2v-2"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </article>

            <aside class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm" aria-label="Media partner lainnya">
                <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Media Partner Lainnya</h3>
                <div class="space-y-3">
                    @forelse($otherPartners as $other)
                        <a href="{{ route('public.media-partners.show', $other->slug) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-3 hover:bg-blue-50 transition-colors">
                            <div class="w-14 h-14 rounded-lg bg-white border border-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($other->logo)
                                    <img src="{{ asset('storage/'.$other->logo) }}" alt="{{ $other->name }}" class="w-full h-full object-contain p-1.5">
                                @else
                                    <span class="text-sm font-black text-[#1d4ed8]">{{ strtoupper(substr($other->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-800 truncate">{{ $other->name }}</div>
                                @if($other->description)
                                    <div class="text-xs text-slate-500 line-clamp-2">{{ $other->description }}</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada media partner lainnya.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>
</x-public-layout>
