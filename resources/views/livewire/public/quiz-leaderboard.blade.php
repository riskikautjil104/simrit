<div wire:poll.2s="refresh">

    {{-- ═══════════════════════════════════════════════
         HERO HEADER
    ═══════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #8B1E1E 0%, #C1272D 50%, #8B1E1E 100%);">
        {{-- Merah putih decorative stripes --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: repeating-linear-gradient(45deg, #ffffff 0px, #ffffff 2px, transparent 2px, transparent 20px);">
        </div>

        {{-- Bunting strip di puncak --}}
        <div class="relative z-10 h-3 w-full" style="background-image: repeating-linear-gradient(60deg, #F2CB6B 0 10px, #ffffff 10px 20px);"></div>

        {{-- Watermark angka 81 --}}
        <div class="absolute -right-6 -bottom-10 text-[11rem] sm:text-[14rem] font-black leading-none select-none pointer-events-none text-white/10" style="font-family: Georgia, 'Times New Roman', serif;">81</div>

        <div class="relative z-10 py-14 text-center px-4">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-[#F2CB6B]/40 rounded-full px-5 py-2 mb-5">
                {{-- Live blinking dot --}}
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                </span>
                <span class="text-white/90 text-xs font-bold tracking-widest uppercase">Live Leaderboard</span>
                <span class="text-white/50 text-[10px] font-mono">• refresh tiap 2 dtk</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-white drop-shadow-lg leading-tight flex items-center justify-center gap-3" style="font-family: Georgia, 'Times New Roman', serif;">
                <svg class="w-8 h-8 text-[#F2CB6B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
                Cerdas Cermat <span class="text-[#F2CB6B]">17 Agustus</span>
                <svg class="w-8 h-8 text-[#F2CB6B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
            </h1>
            <p class="text-white/70 text-sm sm:text-base mt-3">
                Peringatan HUT Republik Indonesia Ke-81 · RSUD chasan boesoirie Ternate
            </p>

            {{-- Live stats bar --}}
            <div class="flex items-center justify-center gap-6 mt-6 flex-wrap">
                <div class="text-center">
                    <div class="text-white font-black text-2xl">{{ $leaders->count() }}</div>
                    <div class="text-white/60 text-xs uppercase tracking-wide">Peserta Aktif</div>
                </div>
                <div class="w-px h-8 bg-[#F2CB6B]/30"></div>
                <div class="text-center">
                    <div class="text-white font-black text-2xl">{{ $totalQuestions }}</div>
                    <div class="text-white/60 text-xs uppercase tracking-wide">Total Soal</div>
                </div>
                <div class="w-px h-8 bg-[#F2CB6B]/30"></div>
                <div class="text-center">
                    <div class="text-[#F2CB6B] font-black text-2xl">{{ $leaders->sum('total_score') }}</div>
                    <div class="text-white/60 text-xs uppercase tracking-wide">Total Poin Terkumpul</div>
                </div>
            </div>
        </div>

        {{-- Perforasi bawah ala perangko --}}
        <div class="relative z-10 h-4" style="background-image: radial-gradient(circle, rgba(255,255,255,0.9) 3px, transparent 3.5px); background-size: 16px 16px; background-position: center top;"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10" style="background-image:radial-gradient(rgba(193,39,45,0.04) 1px, transparent 1px); background-size:16px 16px;">

        {{-- ═══════════════════════════════════════════════
             PODIUM — TOP 3
        ═══════════════════════════════════════════════ --}}
        @if($leaders->count() > 0)
        <div class="flex items-end justify-center gap-4 sm:gap-8 mb-12">

            {{-- 2nd Place --}}
            @if($leaders->count() > 1)
                @php $second = $leaders[1]; @endphp
                <div class="text-center flex flex-col items-center">
                    <div class="w-14 h-14 sm:w-18 sm:h-18 rounded-full bg-gradient-to-br from-slate-200 to-slate-400 border-4 border-slate-300 flex items-center justify-center text-lg sm:text-xl font-black text-slate-700 shadow-lg mb-2">2</div>
                    <div class="font-bold text-[#5A1414] text-xs sm:text-sm max-w-[80px] truncate">{{ $second->name }}</div>
                    <div class="text-[10px] text-[#8A6A2F] max-w-[80px] truncate">{{ optional($second->quizRegistration)->position ?? 'Peserta' }}</div>
                    <div class="mt-2 font-black text-slate-600 text-sm">{{ $second->total_score }} <span class="font-normal text-[10px]">pts</span></div>
                    <div class="mt-2 w-20 sm:w-24 h-16 bg-gradient-to-t from-slate-300 to-slate-200 rounded-t-lg flex items-center justify-center text-slate-500 font-black text-2xl">2</div>
                </div>
            @endif

            {{-- 1st Place --}}
            @php $first = $leaders[0]; @endphp
            <div class="text-center flex flex-col items-center -mt-6 relative">
                <div class="text-[#F2CB6B] mb-1 animate-bounce">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <div class="rounded-full bg-gradient-to-br from-[#F2CB6B] to-[#C89B3C] border-4 border-[#F2CB6B] flex items-center justify-center text-2xl sm:text-3xl font-black text-[#5A1414] shadow-2xl mb-2" style="width:5rem;height:5rem;">1</div>
                <div class="font-extrabold text-[#5A1414] text-sm sm:text-base max-w-[100px] truncate">{{ $first->name }}</div>
                <div class="text-[10px] text-[#8A6A2F] max-w-[100px] truncate">{{ optional($first->quizRegistration)->position ?? 'Peserta' }}</div>
                <div class="mt-2 font-black text-[#C89B3C] text-lg">{{ $first->total_score }} <span class="font-normal text-xs">pts</span></div>
                <div class="mt-2 w-20 sm:w-28 h-24 bg-gradient-to-t from-[#C89B3C] to-[#F2CB6B] rounded-t-lg flex items-center justify-center text-[#5A1414] font-black text-3xl shadow-lg">1</div>
            </div>

            {{-- 3rd Place --}}
            @if($leaders->count() > 2)
                @php $third = $leaders[2]; @endphp
                <div class="text-center flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-200 to-amber-500 border-4 border-amber-400 flex items-center justify-center text-lg sm:text-xl font-black text-amber-900 shadow-lg mb-2">3</div>
                    <div class="font-bold text-[#5A1414] text-xs sm:text-sm max-w-[80px] truncate">{{ $third->name }}</div>
                    <div class="text-[10px] text-[#8A6A2F] max-w-[80px] truncate">{{ optional($third->quizRegistration)->position ?? 'Peserta' }}</div>
                    <div class="mt-2 font-black text-slate-600 text-sm">{{ $third->total_score }} <span class="font-normal text-[10px]">pts</span></div>
                    <div class="mt-2 w-20 sm:w-24 h-12 bg-gradient-to-t from-amber-500 to-amber-400 rounded-t-lg flex items-center justify-center text-amber-100 font-black text-2xl">3</div>
                </div>
            @endif
        </div>
        @endif

        {{-- ═══════════════════════════════════════════════
             FULL RANKINGS TABLE
        ═══════════════════════════════════════════════ --}}
        <div class="bg-[#FDF8ED] rounded-2xl shadow-xl overflow-hidden border-2 border-dashed border-[#D9B36C]">
            {{-- Table header bar --}}
            <div class="flex items-center justify-between px-5 py-3" style="background: linear-gradient(90deg, #8B1E1E 0%, #C1272D 100%);">
                <span class="text-white font-bold text-sm tracking-wide flex items-center gap-2" style="font-family: Georgia, 'Times New Roman', serif;">
                    <svg class="w-4 h-4 text-[#F2CB6B]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Peringkat Peserta
                </span>
                <span class="text-white/70 text-[11px] flex items-center gap-1.5 font-mono">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                    </span>
                    Terakhir: {{ $lastRefreshed }}
                </span>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr class="bg-[#FFF7E0] text-left">
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#8A6A2F] uppercase tracking-wider w-14">#</th>
                        <th class="px-4 py-3 text-xs font-bold text-[#8A6A2F] uppercase tracking-wider">Nama / Jabatan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#8A6A2F] uppercase tracking-wider">Skor</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-[#8A6A2F] uppercase tracking-wider hidden sm:table-cell">Benar</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-[#8A6A2F] uppercase tracking-wider hidden md:table-cell">Selesai Pukul</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3D3AC]">
                    @forelse($leaders as $i => $leader)
                        <tr class="transition-colors duration-200
                            {{ $i === 0 ? 'bg-[#FFF7E0] hover:bg-[#FDECC0]' : ($i === 1 ? 'bg-slate-50 hover:bg-slate-100/70' : ($i === 2 ? 'bg-amber-50 hover:bg-amber-100/50' : 'hover:bg-[#FFF7E0]/50')) }}">

                            {{-- Rank badge --}}
                            <td class="px-4 py-3 text-center">
                                @if($i === 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#F2CB6B] text-[#5A1414] text-xs font-black shadow">1</span>
                                @elseif($i === 1)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-700 text-xs font-black shadow">2</span>
                                @elseif($i === 2)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-amber-900 text-xs font-black shadow">3</span>
                                @else
                                    <span class="text-[#8A6A2F] text-xs font-bold">#{{ $i + 1 }}</span>
                                @endif
                            </td>

                            {{-- Name & position --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar initials --}}
                                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white text-xs shadow-sm"
                                         style="background: hsl({{ ($i * 47) % 360 }}, 65%, 45%)">
                                        {{ strtoupper(substr($leader->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#3B2A16] text-sm leading-tight">{{ $leader->name }}</div>
                                        <div class="text-[11px] text-[#8A6A2F] leading-tight">{{ optional($leader->quizRegistration)->position ?? 'Peserta' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Score --}}
                            <td class="px-4 py-3 text-center">
                                <span class="font-black text-base
                                    {{ $i === 0 ? 'text-[#C89B3C]' : ($i < 3 ? 'text-[#C1272D]' : 'text-[#5A4632]') }}">
                                    {{ $leader->total_score }}
                                </span>
                                <span class="text-[10px] text-[#8A6A2F] ml-0.5">pts</span>
                            </td>

                            {{-- Correct answers --}}
                            <td class="px-4 py-3 text-center hidden sm:table-cell">
                                <span class="text-sm font-bold text-[#5A4632]">{{ $leader->correct_count }}</span>
                                <span class="text-[#D9B36C] mx-0.5">/</span>
                                <span class="text-xs text-[#8A6A2F]">{{ $totalQuestions }}</span>
                            </td>

                            {{-- Finish time --}}
                            <td class="px-4 py-3 text-right hidden md:table-cell">
                                @if($leader->finished_at)
                                    <span class="text-xs text-[#5A4632] font-mono">
                                        {{ $leader->finished_at->format('d M · H:i:s') }}
                                    </span>
                                @else
                                    <span class="text-xs text-[#C1272D] font-semibold animate-pulse flex items-center justify-end gap-1">
                                        <svg class="w-3.5 h-3.5 text-[#C1272D] animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Sedang Mengerjakan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="w-12 h-12 rounded-full bg-[#FFF7E0] border-2 border-dashed border-[#D9B36C] flex items-center justify-center text-[#C89B3C] mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div class="text-[#8A6A2F] font-semibold">Belum ada peserta yang mengerjakan kuis.</div>
                                <div class="text-[#B89B6A] text-xs mt-1">Leaderboard akan muncul secara otomatis setelah peserta mulai menjawab soal.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bottom note --}}
        <p class="text-center text-xs text-[#8A6A2F] mt-6 flex items-center justify-center gap-1 font-mono">
            <svg class="w-3.5 h-3.5 text-[#8A6A2F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17"/></svg>
            Papan skor diperbarui otomatis setiap 2 detik &nbsp;|&nbsp; HUT RI Ke-81 · 17 Agustus 2026
        </p>

        {{-- Bunting strip penutup --}}
        <div class="h-3 w-full mt-6 rounded-full overflow-hidden shadow-inner" style="background-image: repeating-linear-gradient(60deg, #C1272D 0 10px, #ffffff 10px 20px);"></div>
    </div>
</div>