<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header berita">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Berita</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Berita & Informasi</h1>
        </div>
    </section>

    {{-- Main Filter and List Section --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Search and filters form --}}
        <form method="GET" action="{{ route('public.news') }}" class="mb-10 grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-6 relative">
                <label for="search-input" class="sr-only">Cari berita...</label>
                <input
                    type="text"
                    id="search-input"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-input w-full pl-10"
                    placeholder="Cari kata kunci berita..."
                >
            </div>
            <div class="md:col-span-4">
                <label for="category-select" class="sr-only">Kategori</label>
                <select id="category-select" name="category" class="form-select w-full">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->news_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full justify-center">Filter</button>
            </div>
        </form>

        {{-- News Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($news as $n)
                <article class="card overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="aspect-video w-full bg-slate-100 relative overflow-hidden">
                            @if($n->cover_image)
                                <img src="{{ asset('storage/'.$n->cover_image) }}" alt="{{ $n->title }}" class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold bg-[#eff6ff]">SIMRIT NEWS</div>
                            @endif
                            @if($n->category)
                                <span class="absolute top-3 left-3 bg-[#1d4ed8] text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider">
                                    {{ $n->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="text-[11px] text-slate-400 font-medium mb-1">
                                {{ $n->published_at ? $n->published_at->translatedFormat('d M Y') : $n->created_at->translatedFormat('d M Y') }}
                            </div>
                            <h2 class="text-base font-bold text-slate-800 leading-snug mb-2 hover:text-[#1d4ed8] transition-colors">
                                <a href="{{ route('public.news.show', $n->slug) }}">{{ $n->title }}</a>
                            </h2>
                            <p class="text-slate-500 text-xs leading-relaxed">
                                {{ Str::limit($n->excerpt, 120) }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-1">
                        <a href="{{ route('public.news.show', $n->slug) }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold inline-flex items-center gap-1">
                            Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3>Tidak ditemukan berita</h3>
                    <p>Silakan coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $news->links() }}
        </div>
    </section>
</x-public-layout>
