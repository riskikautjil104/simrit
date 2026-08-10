<x-public-layout>
    {{-- Header Banner --}}
    <section class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white py-12" aria-label="Header dokumen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-blue-200/80 mb-2 flex items-center gap-1.5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-white font-semibold">Unduh Dokumen</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight">Pusat Unduh Dokumen IT</h1>
        </div>
    </section>

    {{-- Main Document Area --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Category Filter Sidebar --}}
            <aside class="lg:col-span-3 bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4" aria-label="Kategori dokumen">
                <h3 class="text-[#1e3a8a] font-bold text-xs uppercase tracking-wider">Kategori Berkas</h3>
                <nav class="flex flex-col gap-1" aria-label="Menu kategori">
                    <a href="{{ route('public.documents') }}" class="px-3 py-2 rounded-lg text-sm transition-all
                        {{ !request('category') ? 'bg-blue-50 text-[#1d4ed8] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('public.documents', ['category' => $cat->slug]) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all
                            {{ request('category') === $cat->slug ? 'bg-blue-50 text-[#1d4ed8] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold">
                                {{ $cat->documents_count }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            {{-- Main List area --}}
            <div class="lg:col-span-9 space-y-6">
                {{-- Search filter --}}
                <form method="GET" action="{{ route('public.documents') }}" class="flex gap-3">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative flex-1">
                        <label for="search-doc" class="sr-only">Cari dokumen...</label>
                        <input
                            type="text"
                            id="search-doc"
                            name="q"
                            value="{{ request('q') }}"
                            class="form-input w-full pl-10"
                            placeholder="Cari judul dokumen..."
                        >
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>

                {{-- Document List table --}}
                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="table-wrap">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th scope="col">Nama Dokumen</th>
                                    <th scope="col" class="hidden sm:table-cell">Kategori</th>
                                    <th scope="col" class="hidden md:table-cell">Ukuran</th>
                                    <th scope="col" class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="font-bold text-slate-800 text-sm">{{ $doc->title }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5 truncate max-w-xs">
                                                {{ $doc->description ?: $doc->original_filename }}
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell">
                                            @if($doc->category)
                                                <span class="text-xs text-slate-600 bg-slate-50 border border-slate-100 px-2 py-0.5 rounded font-medium">
                                                    {{ $doc->category->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="hidden md:table-cell text-xs text-slate-500">
                                            {{ $doc->formatted_size }}
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('public.documents.download', $doc->id) }}" class="btn btn-sm btn-ghost inline-flex items-center gap-1">
                                                <span>⬇️</span>
                                                <span class="hidden sm:inline">Unduh</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400 empty-state">
                                            <svg class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <h3 class="mt-2">Dokumen tidak ditemukan</h3>
                                            <p>Belum ada dokumen pada kategori ini atau kata kunci pencarian Anda salah.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
