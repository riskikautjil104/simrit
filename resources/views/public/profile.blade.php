<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs and title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-blue-100 font-medium">Profil</span>
                <span>/</span>
                <span class="text-white font-semibold">{{ $page->title }}</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">{{ $page->title }}</h1>
        </div>
    </section>

    {{-- Main Profile Page Content --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Content Area --}}
            <article class="lg:col-span-8 bg-white border border-slate-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                @if($page->featured_image)
                    @if($page->slug === 'struktur-organisasi')
                        <div class="mb-6 rounded-xl overflow-auto bg-slate-50 border border-slate-100 p-3">
                            <img src="{{ asset('storage/'.$page->featured_image) }}" alt="Ilustrasi {{ $page->title }}" class="w-full h-auto object-contain rounded-lg">
                        </div>
                    @else
                        <div class="mb-6 rounded-xl overflow-hidden aspect-video max-h-96 w-full">
                            <img src="{{ asset('storage/'.$page->featured_image) }}" alt="Ilustrasi {{ $page->title }}" class="object-cover w-full h-full">
                        </div>
                    @endif
                @endif
                <div class="prose max-w-none text-slate-600">
                    {!! $page->content !!}
                </div>
            </article>

            {{-- Sidebar Links --}}
            <aside class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-5" aria-label="Menu navigasi profil">
                <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider">Navigasi Profil</h3>
                <nav class="flex flex-col gap-1.5" aria-label="Menu profil">
                    @php
                        $profiles = [
                            ['slug' => 'sejarah',            'title' => 'Sejarah Ruang IT'],
                            ['slug' => 'visi-misi',          'title' => 'Visi & Misi'],
                            ['slug' => 'struktur-organisasi','title' => 'Struktur Organisasi'],
                            ['slug' => 'tugas-fungsi',       'title' => 'Tugas & Fungsi'],
                            ['slug' => 'sarana-prasarana',   'title' => 'Sarana & Prasarana'],
                        ];
                    @endphp
                    @foreach($profiles as $p)
                        <a href="{{ route('public.profile', $p['slug']) }}" class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm transition-all
                            {{ request()->route('slug') === $p['slug'] ? 'bg-blue-50 text-[#1d4ed8] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>{{ $p['title'] }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </nav>
            </aside>
        </div>
    </section>
</x-public-layout>
