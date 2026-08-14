@props([
    'title' => null,
    'metaDescription' => null,
    'metaImage' => null,
    'metaType' => 'website',
    'schema' => null,
])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'SIMRIT Chasan Boesoirie');
        $description = $metaDescription ?? \App\Models\Setting::get('site_description', 'Portal informasi resmi Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate, Maluku Utara.');
        $faviconSetting = \App\Models\Setting::get('favicon');
        $faviconUrl = $faviconSetting ? asset('storage/'.$faviconSetting) : asset('favicon.ico');
        $shareImage = $metaImage ?: asset('logo/logoruangit.png');
    @endphp
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="RSUD Ternate, RSUD Dr. H. Chasan Boesoirie, Ternate, Maluku Utara, rumah sakit Ternate, berita Ternate, Ruang IT RSUD">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    <title>{{ $title ?? config('app.name') }} — {{ $siteName }}</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ file_exists(public_path('favicon-96x96.png')) ? asset('favicon-96x96.png') : $faviconUrl }}">
    <link rel="icon" type="image/svg+xml" href="{{ file_exists(public_path('favicon.svg')) ? asset('favicon.svg') : $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ file_exists(public_path('apple-touch-icon.png')) ? asset('apple-touch-icon.png') : $faviconUrl }}">
    <meta name="apple-mobile-web-app-title" content="SIMRIT">
    <link rel="manifest" href="{{ file_exists(public_path('site.webmanifest')) ? asset('site.webmanifest') : asset('site.webmanifest') }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $title ?? $siteName }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? $siteName }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    @if($schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#f8fafc] text-slate-800 pb-24 lg:pb-0">

{{-- ── Public Navigation ──────────────────────────────────── --}}
<header class="public-nav" role="banner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo & Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="Beranda SIMRIT">
                @if(\App\Models\Setting::get('logo'))
                    <img src="{{ asset('storage/'.\App\Models\Setting::get('logo')) }}" alt="Logo SIMRIT" class="h-9 w-auto">
                @else
                    <div class="h-9 w-9 rounded-lg flex items-center justify-center text-white font-bold text-sm" style="background:linear-gradient(135deg,#1d4ed8,#059669)">IT</div>
                @endif
                <div class="leading-tight">
                    <div class="font-bold text-[#1e3a8a] text-sm tracking-tight">{{ \App\Models\Setting::get('site_name','SIMRIT') }}</div>
                    <div class="text-[10px] text-slate-500 hidden sm:block">Ruang IT RSUD Dr. H. Chasan Boesoirie</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi utama">
                @php
                    $publicPortals = \App\Models\Portal::published()->get();
                    $navLinks = [
                        ['href' => route('home'),               'label' => 'Beranda'],
                        ['href' => route('public.profile','sejarah'), 'label' => 'Profil',   'dropdown' => [
                            ['href' => route('public.profile','sejarah'),            'label' => 'Sejarah'],
                            ['href' => route('public.profile','visi-misi'),          'label' => 'Visi & Misi'],
                            ['href' => route('public.profile','struktur-organisasi'),'label' => 'Struktur Organisasi'],
                            ['href' => route('public.profile','tugas-fungsi'),       'label' => 'Tugas & Fungsi'],
                            ['href' => route('public.profile','sarana-prasarana'),   'label' => 'Sarana & Prasarana'],
                        ]],
                        ...($publicPortals->isNotEmpty() ? [[
                            'href' => '#',
                            'label' => 'Portal',
                            'dropdown' => $publicPortals->map(fn($portal) => [
                                'href' => $portal->link,
                                'label' => $portal->name,
                                'external' => true,
                            ])->all(),
                        ]] : []),
                        ['href' => route('public.services'),    'label' => 'Layanan'],
                        ['href' => route('public.news'),        'label' => 'Berita'],
                        ['href' => route('public.events'),      'label' => 'Kegiatan'],
                        ['href' => route('public.documents'),   'label' => 'Dokumen'],
                        ['href' => route('public.galleries'),   'label' => 'Galeri'],
                        ['href' => route('public.videos'),      'label' => 'Video'],
                        ['href' => route('public.team'),        'label' => 'Tim IT'],
                        ['href' => route('public.quiz.register'),'label' => 'Lomba 17-an'],
                    ];
                @endphp
                @foreach($navLinks as $link)
                    @if(isset($link['dropdown']))
                        <div class="relative group">
                            <a href="{{ $link['href'] }}" class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-[#1d4ed8] hover:bg-blue-50" aria-haspopup="true">
                                {{ $link['label'] }}
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                            <div class="absolute top-full left-0 pt-1 w-52 hidden group-hover:block z-50">
                                <div class="bg-white rounded-xl shadow-xl border border-slate-100 py-1.5">
                                    @foreach($link['dropdown'] as $sub)
                                        <a href="{{ $sub['href'] }}" @if(!empty($sub['external'])) target="_blank" rel="noopener" @endif class="block px-4 py-2 text-sm text-slate-600 hover:text-[#1d4ed8] hover:bg-blue-50 transition-colors">{{ $sub['label'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $link['href'] }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-[#1d4ed8] hover:bg-blue-50 transition-colors
                            {{ request()->url() === $link['href'] ? 'text-[#1d4ed8] bg-blue-50 font-semibold' : '' }}">
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Mobile burger --}}
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100" role="navigation" aria-label="Menu mobile">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">
            <a href="{{ route('home') }}"             class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Beranda</a>
            <a href="{{ route('public.profile','sejarah') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Sejarah</a>
            <a href="{{ route('public.profile','visi-misi') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Visi & Misi</a>
            <a href="{{ route('public.profile','struktur-organisasi') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Struktur Organisasi</a>
            <a href="{{ route('public.profile','tugas-fungsi') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Tugas & Fungsi</a>
            @if(isset($publicPortals) && $publicPortals->isNotEmpty())
                <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Portal</div>
                @foreach($publicPortals as $portal)
                    <a href="{{ $portal->link }}" target="_blank" rel="noopener" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">{{ $portal->name }}</a>
                @endforeach
            @endif
            <a href="{{ route('public.services') }}"  class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Layanan IT</a>
            <a href="{{ route('public.news') }}"      class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Berita</a>
            <a href="{{ route('public.events') }}"    class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Kegiatan</a>
            <a href="{{ route('public.documents') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Dokumen</a>
            <a href="{{ route('public.galleries') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Galeri</a>
            <a href="{{ route('public.videos') }}"    class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Video</a>
            <a href="{{ route('public.team') }}"      class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8]">Tim IT</a>
            <a href="{{ route('public.quiz.register') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-[#1d4ed8] font-bold text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21v11h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                Lomba 17-an
            </a>
        </div>
    </div>
</header>

{{-- ── Mobile Bottom Navigator ───────────────────────────── --}}
<nav class="fixed inset-x-3 bottom-3 z-[150] lg:hidden" aria-label="Navigasi cepat mobile">
    <div class="grid grid-cols-5 items-center rounded-2xl border border-slate-200/80 bg-white/95 px-2 py-2 shadow-2xl backdrop-blur-md">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 rounded-xl px-2 py-1.5 text-[10px] font-bold {{ request()->routeIs('home') ? 'text-[#1d4ed8] bg-blue-50' : 'text-slate-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-8 9 8M5 10v10h14V10M9 20v-6h6v6"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('public.news') }}" class="flex flex-col items-center gap-1 rounded-xl px-2 py-1.5 text-[10px] font-bold {{ request()->routeIs('public.news*') ? 'text-[#1d4ed8] bg-blue-50' : 'text-slate-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2M7 8h6v4H7V8zm0 8h6"/></svg>
            <span>Berita</span>
        </a>
        @if(isset($publicPortals) && $publicPortals->isNotEmpty())
            <a href="{{ $publicPortals->first()->link }}" target="_blank" rel="noopener" class="relative -mt-7 flex flex-col items-center gap-1 rounded-2xl bg-gradient-to-br from-[#1d4ed8] to-[#059669] px-2 py-3 text-[10px] font-bold text-white shadow-lg">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.172-1.172m3.536 3.536l4.242-4.242m-1.414 5.656l1.172-1.172a4 4 0 00-5.656-5.656l-3 3"/></svg>
                <span>Portal</span>
            </a>
        @else
            <a href="{{ route('public.services') }}" class="relative -mt-7 flex flex-col items-center gap-1 rounded-2xl bg-gradient-to-br from-[#1d4ed8] to-[#059669] px-2 py-3 text-[10px] font-bold text-white shadow-lg">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                <span>Layanan</span>
            </a>
        @endif
        <a href="{{ route('public.galleries') }}" class="flex flex-col items-center gap-1 rounded-xl px-2 py-1.5 text-[10px] font-bold {{ request()->routeIs('public.galleries*') ? 'text-[#1d4ed8] bg-blue-50' : 'text-slate-500' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Galeri</span>
        </a>
        <button id="bottom-menu-btn" type="button" class="flex flex-col items-center gap-1 rounded-xl px-2 py-1.5 text-[10px] font-bold text-slate-500" aria-label="Buka menu lengkap" aria-expanded="false" aria-controls="mobile-menu">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Menu</span>
        </button>
    </div>
</nav>

{{-- ── Page Content ──────────────────────────────────────── --}}
<main id="main-content">
    {{ $slot }}
</main>

{{-- ── Footer ────────────────────────────────────────────── --}}
<footer class="bg-[#0f172a] text-slate-300 pt-14 pb-6 mt-16" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold" style="background:linear-gradient(135deg,#1d4ed8,#059669)">IT</div>
                    <div>
                        <div class="text-white font-bold text-base">{{ \App\Models\Setting::get('site_name','SIMRIT Chasan Boesoirie') }}</div>
                        <div class="text-slate-400 text-xs">{{ \App\Models\Setting::get('site_tagline','Sistem Informasi Manajemen Ruang IT') }}</div>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                    Portal informasi resmi Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate. Melayani dengan teknologi, mendukung pelayanan kesehatan yang lebih baik.
                </p>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Navigasi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.news') }}"      class="hover:text-white transition-colors">Berita</a></li>
                    <li><a href="{{ route('public.events') }}"    class="hover:text-white transition-colors">Kegiatan</a></li>
                    <li><a href="{{ route('public.documents') }}" class="hover:text-white transition-colors">Dokumen</a></li>
                    <li><a href="{{ route('public.galleries') }}" class="hover:text-white transition-colors">Galeri Foto</a></li>
                    <li><a href="{{ route('public.videos') }}"    class="hover:text-white transition-colors">Video</a></li>
                    <li><a href="{{ route('public.team') }}"      class="hover:text-white transition-colors">Tim IT</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Kontak</h3>
                <ul class="space-y-2.5 text-sm">
                    @if(\App\Models\Setting::get('address'))
                    <li class="flex gap-2">
                        <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-slate-400 leading-snug">{{ \App\Models\Setting::get('address') }}</span>
                    </li>
                    @endif
                    @if(\App\Models\Setting::get('phone'))
                    <li class="flex gap-2 items-center">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ \App\Models\Setting::get('phone') }}" class="text-slate-400 hover:text-white">{{ \App\Models\Setting::get('phone') }}</a>
                    </li>
                    @endif
                    @if(\App\Models\Setting::get('email'))
                    <li class="flex gap-2 items-center">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ \App\Models\Setting::get('email') }}" class="text-slate-400 hover:text-white">{{ \App\Models\Setting::get('email') }}</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500">
            <span>{{ \App\Models\Setting::get('footer_text', '© '.date('Y').' Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate') }}</span>
            {{-- <a href="{{ route('login') }}" class="hover:text-white transition-colors text-xs">Admin Login</a> --}}
        </div>
    </div>
</footer>

	<script>
	    // Mobile menu toggle
	    const mobileBtn = document.getElementById('mobile-menu-btn');
	    const bottomMenuBtn = document.getElementById('bottom-menu-btn');
	    const mobileMenu = document.getElementById('mobile-menu');
	    const toggleMobileMenu = () => {
	        if (!mobileMenu) return;
	            const expanded = mobileMenu.classList.toggle('hidden');
	        mobileBtn?.setAttribute('aria-expanded', !expanded);
	        bottomMenuBtn?.setAttribute('aria-expanded', !expanded);
	    };

	    if (mobileBtn && mobileMenu) {
	        mobileBtn.addEventListener('click', toggleMobileMenu);
	    }
	    bottomMenuBtn?.addEventListener('click', toggleMobileMenu);
	</script>

@livewireScripts
</body>
</html>
