<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('public.news') }}" class="hover:text-white transition-colors">Berita</a>
                <span>/</span>
                <span class="text-white font-semibold truncate max-w-[200px]">{{ $news->title }}</span>
            </nav>
            <div class="flex items-center gap-2 mb-2">
                @if($news->category)
                    <span class="bg-emerald-500 text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider">
                        {{ $news->category->name }}
                    </span>
                @endif
                <span class="text-xs text-blue-200">
                    {{ $news->published_at ? $news->published_at->translatedFormat('d M Y') : $news->created_at->translatedFormat('d M Y') }}
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $news->title }}</h1>
        </div>
    </section>

    {{-- Main Post Content --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Article body --}}
            <article class="lg:col-span-8 bg-white border border-slate-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                @if($news->cover_image)
                    <div class="mb-6 rounded-xl overflow-hidden aspect-video max-h-96 w-full shadow-sm">
                        <img src="{{ asset('storage/'.$news->cover_image) }}" alt="{{ $news->title }}" class="object-cover w-full h-full">
                    </div>
                @endif

                <div class="prose max-w-none text-slate-600">
                    {!! $news->content !!}
                </div>

                <div class="border-t border-slate-100 pt-6 mt-8 flex items-center justify-between text-xs text-slate-400">
                    <span>Penulis: {{ $news->creator ? $news->creator->name : 'Administrator' }}</span>
                </div>
            </article>

            {{-- Sidebar related posts --}}
            <aside class="lg:col-span-4 space-y-6" aria-label="Berita terkait">
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider mb-4">Berita Terkait</h3>
                    <div class="space-y-4">
                        @forelse($related as $r)
                            <div class="flex gap-3">
                                @if($r->cover_image)
                                    <img src="{{ asset('storage/'.$r->cover_image) }}" alt="" class="w-14 h-14 rounded-lg object-cover bg-slate-100 flex-shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-blue-50 text-xs flex items-center justify-center font-bold text-slate-400 flex-shrink-0">NEWS</div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-800 hover:text-[#1d4ed8] line-clamp-2">
                                        <a href="{{ route('public.news.show', $r->slug) }}">{{ $r->title }}</a>
                                    </h4>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $r->published_at ? $r->published_at->format('d M Y') : $r->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Tidak ada berita terkait lainnya.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-public-layout>
