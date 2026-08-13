<x-public-layout>
    {{-- Hero Section --}}
    <section class="hero-gradient text-white py-20 lg:py-28 relative overflow-hidden" aria-label="Hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md border border-white/20 text-white uppercase tracking-wider">
                        Portal Resmi Ruang IT
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                        Transformasi Digital Layanan Kesehatan
                    </h1>
                    <p class="text-white/80 text-lg leading-relaxed max-w-xl">
                        Selamat datang di SIMRIT RSUD Dr. H. Chasan Boesoirie Ternate. Kami berkomitmen menyediakan infrastruktur teknologi, pengembangan sistem informasi, dan dukungan teknis handal demi efisiensi layanan rumah sakit.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('public.services') }}" class="btn btn-lg bg-white text-[#1d4ed8] hover:bg-slate-50 font-bold shadow-lg">
                            Layanan IT
                        </a>
                        <a href="{{ route('public.profile', 'visi-misi') }}" class="btn btn-lg btn-ghost border-white text-white hover:bg-white/10 font-bold">
                            Visi & Misi
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block relative justify-self-center">
                    {{-- Graphic placeholder --}}
                    <div class="w-80 h-80 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 absolute -top-10 -left-10 animate-pulse"></div>
                    <div class="w-96 h-96 rounded-full bg-gradient-to-tr from-emerald-500/20 to-blue-500/20 backdrop-blur-3xl border border-white/10 flex items-center justify-center p-8 shadow-2xl relative">
                        <div class="text-center">
                            <div class="text-7xl font-black tracking-widest text-white/90">SIRSIT</div>
                            <div class="text-xs uppercase tracking-widest text-emerald-400 font-bold mt-2">RSUD Dr. H. Chasan Boesoirie</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute -bottom-2 -left-2 right-0 h-16 bg-[#f8fafc] rounded-t-[50%]"></div>
    </section>

    {{-- Short Profile & Vision-Mission --}}
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Profil Singkat">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-5">
                <span class="text-xs font-bold text-[#1d4ed8] uppercase tracking-wider">Tentang Kami</span>
                <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tight">Menjaga Kestabilan Sistem & Inovasi Rumah Sakit</h2>
                <p class="text-slate-600 leading-relaxed">
                    Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate bertanggung jawab penuh terhadap operasional teknologi informasi, mulai dari pengelolaan SIMRS (Sistem Informasi Manajemen Rumah Sakit), infrastruktur jaringan kabel & nirkabel, keamanan data, penyediaan sarana IT hingga pelatihan teknis bagi staf.
                </p>
                <div>
                    <a href="{{ route('public.profile', 'sejarah') }}" class="btn btn-ghost">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-100 p-8 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full -mr-16 -mt-16"></div>
                <div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-[#1d4ed8] font-bold mb-4">V</div>
                    <h3 class="text-lg font-bold text-[#1e3a8a] mb-2">Visi Ruang IT</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Menjadi unit IT yang profesional, inovatif, dan terpercaya dalam mendukung transformasi digital pelayanan kesehatan di Maluku Utara.
                    </p>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold mb-4">M</div>
                    <h3 class="text-lg font-bold text-[#1e3a8a] mb-2">Misi Utama</h3>
                    <ul class="text-slate-500 text-sm space-y-2 leading-relaxed">
                        <li class="flex gap-2">
                            <span class="text-emerald-500 font-bold">•</span> Mengembangkan SIMRS yang terintegrasi.
                        </li>
                        <li class="flex gap-2">
                            <span class="text-emerald-500 font-bold">•</span> Mengelola jaringan server aman dan berkehandalan tinggi.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- IT Services Section --}}
    <section class="py-16 bg-slate-50" aria-label="Layanan Utama">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold text-[#1d4ed8] uppercase tracking-wider">Solusi Kami</span>
                <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tight mt-2">Layanan Teknologi Informasi</h2>
                <p class="text-slate-500 text-sm mt-2">Dukungan operasional dan integrasi sistem digital untuk mendukung efisiensi pelayanan medis dan administrasi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($services as $svc)
                    <div class="card p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-[#1d4ed8] text-xl font-bold mb-5">
                                @if($svc->icon)
                                    {!! $svc->icon !!}
                                @else
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 3.75h4.5A1.75 1.75 0 0116 5.5v13A1.75 1.75 0 0114.25 20h-4.5A1.75 1.75 0 018 18.5v-13a1.75 1.75 0 011.75-1.75zM11 17h2M10.5 6h3"/></svg>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-[#1e3a8a] mb-2">{{ $svc->title }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                {{ $svc->short_description }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('public.services.show', $svc->slug) }}" class="text-[#1d4ed8] hover:text-[#1e3a8a] text-sm font-semibold inline-flex items-center gap-1">
                                Detil Layanan
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-slate-400">Belum ada layanan yang ditayangkan.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Announcements, News, and Events --}}
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- News & Events (Col-span 8) --}}
            <div class="lg:col-span-8 space-y-12">
                {{-- News --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-[#1e3a8a] tracking-tight">Berita Terkini</h2>
                            <p class="text-slate-500 text-xs mt-1">Kabar terbaru seputar operasional IT dan teknologi kesehatan.</p>
                        </div>
                        <a href="{{ route('public.news') }}" class="text-[#1d4ed8] hover:underline text-sm font-semibold inline-flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($latestNews as $n)
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
                                    <div class="p-5">
                                        <div class="text-[11px] text-slate-400 font-medium mb-1">
                                            {{ $n->published_at ? $n->published_at->translatedFormat('d M Y') : $n->created_at->translatedFormat('d M Y') }}
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800 leading-snug mb-2 hover:text-[#1d4ed8] transition-colors">
                                            <a href="{{ route('public.news.show', $n->slug) }}">{{ Str::limit($n->title, 55) }}</a>
                                        </h3>
                                        <p class="text-slate-500 text-xs leading-relaxed">
                                            {{ Str::limit($n->excerpt, 100) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="px-5 pb-5 pt-1">
                                    <a href="{{ route('public.news.show', $n->slug) }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold inline-flex items-center gap-1">
                                        Selengkapnya
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full text-center py-6 text-slate-400 bg-white rounded-xl border border-slate-100">Belum ada berita.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Events --}}
                <div class="space-y-6 pt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-[#1e3a8a] tracking-tight">Agenda Kegiatan</h2>
                            <p class="text-slate-500 text-xs mt-1">Jadwal agenda pemeliharaan, implementasi sistem, dan rapat IT.</p>
                        </div>
                        <a href="{{ route('public.events') }}" class="text-[#1d4ed8] hover:underline text-sm font-semibold inline-flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($latestEvents as $ev)
                            <div class="bg-white border border-slate-100 rounded-xl p-4 flex gap-4 hover:shadow-md transition-shadow">
                                <div class="w-16 h-16 rounded-lg bg-blue-50 flex-shrink-0 flex flex-col items-center justify-center text-[#1d4ed8] border border-blue-100">
                                    <span class="text-lg font-black leading-none">{{ $ev->starts_at->format('d') }}</span>
                                    <span class="text-[10px] uppercase font-bold mt-1">{{ $ev->starts_at->format('M') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-bold uppercase tracking-wider">
                                            {{ $ev->location }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $ev->starts_at->format('H:i') }} - {{ $ev->ends_at ? $ev->ends_at->format('H:i') : 'Selesai' }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 hover:text-[#1d4ed8] truncate">
                                        <a href="{{ route('public.events.show', $ev->slug) }}">{{ $ev->title }}</a>
                                    </h3>
                                    <p class="text-slate-500 text-xs line-clamp-1 mt-1">
                                        {{ strip_tags($ev->description) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 bg-white rounded-xl border border-slate-100">Belum ada agenda kegiatan terdekat.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Announcements Column (Col-span 4) --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-gradient-to-b from-[#1e3a8a] to-[#1e2e5f] text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-10 -mt-10"></div>
                    <div class="flex items-center gap-2 mb-6">
                        <h2 class="text-lg font-bold tracking-tight">Pengumuman Resmi</h2>
                    </div>

                    <div class="space-y-6">
                        @forelse($announcements as $ann)
                            <div class="border-b border-white/10 pb-4 last:border-none last:pb-0">
                                <div class="text-[10px] text-emerald-300 font-bold mb-1">
                                    {{ $ann->published_at ? $ann->published_at->translatedFormat('d M Y') : $ann->created_at->translatedFormat('d M Y') }}
                                </div>
                                <h3 class="text-sm font-bold leading-snug hover:text-emerald-300 transition-colors">
                                    {{ $ann->title }}
                                </h3>
                                <div class="text-white/70 text-xs mt-2 line-clamp-3 leading-relaxed">
                                    {!! strip_tags($ann->content) !!}
                                </div>
                            </div>
                        @empty
                            <div class="text-white/50 text-xs text-center py-6">Tidak ada pengumuman baru.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-[#1e3a8a] font-bold text-sm uppercase tracking-wider mb-4">Layanan Pengaduan</h3>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">Apabila terdapat kendala teknis darurat pada sistem SIMRS, jaringan atau internet rumah sakit, silakan menghubungi pusat layanan IT.</p>
                    <div class="space-y-2 text-xs">
                        @if(\App\Models\Setting::get('phone'))
                        <div class="flex items-center gap-2 text-slate-600">
                            <span>{{ \App\Models\Setting::get('phone') }}</span>
                        </div>
                        @endif
                        @if(\App\Models\Setting::get('email'))
                        <div class="flex items-center gap-2 text-slate-600">
                            <span>✉️</span>
                            <span>{{ \App\Models\Setting::get('email') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
