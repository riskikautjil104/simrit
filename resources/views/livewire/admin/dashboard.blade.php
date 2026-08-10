<div>
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- News Stat --}}
        <div class="stat-card">
            <div class="stat-icon bg-blue-50 text-blue-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5"/></svg></div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ $stats['news'] }}</div>
                <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mt-0.5">Total Berita</div>
            </div>
        </div>

        {{-- Events Stat --}}
        <div class="stat-card">
            <div class="stat-icon bg-emerald-50 text-emerald-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4h10M7 20h10M8 4v4l4 4-4 4v4M16 4v4l-4 4 4 4v4"/></svg></div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ $stats['events'] }}</div>
                <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mt-0.5">Agenda Kegiatan</div>
            </div>
        </div>

        {{-- Documents Stat --}}
        <div class="stat-card">
            <div class="stat-icon bg-purple-50 text-purple-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5A2.5 2.5 0 016.5 5h4l2 2h5A2.5 2.5 0 0120 9.5v7a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 16.5z"/></svg></div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ $stats['documents'] }}</div>
                <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mt-0.5">Dokumen Resmi</div>
            </div>
        </div>

        {{-- Team Stat --}}
        <div class="stat-card">
            <div class="stat-icon bg-amber-50 text-amber-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 20a8 8 0 0116 0"/></svg></div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ $stats['team'] }}</div>
                <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mt-0.5">Anggota Tim IT</div>
            </div>
        </div>
    </div>

    {{-- Welcome & Audit log panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Welcome box --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/5 rounded-full -mr-20 -mt-20"></div>
            <div class="space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                    Sistem Manajemen SIMRIT
                </span>
                <h2 class="text-2xl font-black text-[#1e3a8a] tracking-tight">Selamat Datang di SIMRIT Admin</h2>
                <p class="text-slate-600 text-sm leading-relaxed max-w-md">
                    Panel ini digunakan untuk mengelola seluruh informasi publik Ruang IT RSUD Dr. H. Chasan Boesoirie. Anda dapat menambahkan berita, menyebarkan pengumuman terbaru, mengunggah SOP/SK resmi, dan memperbarui portofolio layanan.
                </p>
            </div>
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.news') }}" class="btn btn-primary">Tulis Berita Baru</a>
                <a href="{{ route('admin.documents') }}" class="btn btn-secondary">Unggah Dokumen</a>
            </div>
        </div>

        {{-- Recent Logs --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-slate-800 font-bold text-sm mb-4 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2.5 1.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Log Aktivitas Terbaru
                </h3>

                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                    @forelse($recentLogs as $log)
                        <div class="border-b border-slate-100 pb-2.5 last:border-none last:pb-0 text-xs">
                            <div class="flex items-center justify-between text-slate-400 mb-1">
                                <span class="font-bold text-slate-600">{{ $log->user ? $log->user->name : 'System' }}</span>
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 leading-snug">
                                <span class="font-bold text-blue-600 uppercase text-[9px] tracking-wider bg-blue-50 px-1 py-0.5 rounded mr-1">
                                    {{ $log->action }}
                                </span>
                                {{ $log->description }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-12 text-xs">Belum ada rekaman aktivitas log.</div>
                    @endforelse
                </div>
            </div>
            @can('superadmin-only')
                <div class="border-t border-slate-100 pt-3 mt-4 text-center">
                    <a href="{{ route('admin.logs') }}" class="text-xs text-[#1d4ed8] hover:text-[#1e3a8a] font-bold">
                        Lihat Seluruh Log Aktivitas →
                    </a>
                </div>
            @endcan
        </div>
    </div>
</div>
