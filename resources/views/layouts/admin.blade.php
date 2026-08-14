<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — Admin SIMRIT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-100">
<div class="flex min-h-screen">

    {{-- ── Sidebar Overlay (mobile) ─────────────── --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    {{-- ── Admin Sidebar ──────────────────────────── --}}
    <aside id="admin-sidebar" class="admin-sidebar fixed lg:relative z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300" aria-label="Navigasi admin">
        {{-- Brand --}}
        <div class="px-5 py-5 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg flex items-center justify-center text-white font-bold text-sm bg-white/20">IT</div>
                <div>
                    <div class="text-white font-bold text-sm leading-tight">SIMRIT Admin</div>
                    <div class="text-white/50 text-[10px]">Panel Pengelolaan</div>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="py-4 overflow-y-auto" style="max-height: calc(100vh - 140px)">
            <div class="px-3">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <div class="px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest mt-3">Konten</div>

                <a href="{{ route('admin.pages') }}" class="sidebar-link {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Halaman Profil
                </a>
                <a href="{{ route('admin.news') }}" class="sidebar-link {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Berita
                </a>
                <a href="{{ route('admin.events') }}" class="sidebar-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kegiatan
                </a>
                <a href="{{ route('admin.announcements') }}" class="sidebar-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Pengumuman
                </a>

                <hr class="sidebar-divider">

                <div class="px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Media & Dokumen</div>

                <a href="{{ route('admin.documents') }}" class="sidebar-link {{ request()->routeIs('admin.documents*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Dokumen
                </a>
                <a href="{{ route('admin.galleries') }}" class="sidebar-link {{ request()->routeIs('admin.galleries*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Galeri Foto
                </a>
                <a href="{{ route('admin.videos') }}" class="sidebar-link {{ request()->routeIs('admin.videos*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Video
                </a>
                <a href="{{ route('admin.media') }}" class="sidebar-link {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    Pustaka Media
                </a>

                <hr class="sidebar-divider">

                <div class="px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">IT & Lomba</div>

                <a href="{{ route('admin.services') }}" class="sidebar-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Layanan IT
                </a>
                <a href="{{ route('admin.portals') }}" class="sidebar-link {{ request()->routeIs('admin.portals*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.172-1.172m3.536 3.536l4.242-4.242m-1.414 5.656l1.172-1.172a4 4 0 00-5.656-5.656l-3 3"/></svg>
                    Portal
                </a>
                <a href="{{ route('admin.team') }}" class="sidebar-link {{ request()->routeIs('admin.team*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Tim IT
                </a>
                <a href="{{ route('admin.media-partners') }}" class="sidebar-link {{ request()->routeIs('admin.media-partners*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Media Partner
                </a>
                <a href="{{ route('admin.quiz.sessions') }}" class="sidebar-link {{ request()->routeIs('admin.quiz.sessions*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sesi Kuis
                </a>
                <a href="{{ route('admin.quiz.registrations') }}" class="sidebar-link {{ request()->routeIs('admin.quiz.registrations*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Pendaftar Lomba
                </a>
                <a href="{{ route('admin.quiz.questions') }}" class="sidebar-link {{ request()->routeIs('admin.quiz.questions*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Soal Lomba
                </a>

                @can('superadmin-only')
                <hr class="sidebar-divider">
                <div class="px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Superadmin</div>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Manajemen Admin
                </a>
                <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Log Aktivitas
                </a>
                <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Pengaturan
                </a>
                @endcan
            </div>
        </nav>

        {{-- User Info --}}
        <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-xs font-semibold truncate">{{ Auth::user()->name }}</div>
                    <div class="text-white/50 text-[10px] uppercase">{{ Auth::user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-white transition-colors" title="Keluar" aria-label="Keluar">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ──────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 px-4 lg:px-8 h-14 flex items-center justify-between flex-shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100" aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-sm font-semibold text-slate-800">{{ $title ?? 'Dashboard' }}</h1>
                    @isset($breadcrumbs)
                        <nav class="text-xs text-slate-500" aria-label="Breadcrumb">{{ $breadcrumbs }}</nav>
                    @endisset
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#1d4ed8] transition-colors" aria-label="Lihat situs publik">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Situs
                </a>
                <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>
                <span class="text-xs text-slate-600 font-medium hidden sm:block">{{ Auth::user()->name }}</span>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-4 lg:mx-8 mt-4 alert alert-success" data-auto-dismiss="4000" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-4 lg:mx-8 mt-4 alert alert-error" data-auto-dismiss="5000" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-8 overflow-auto" id="admin-content">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
