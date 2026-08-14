{{-- Poll every 1 second for countdown ticker --}}
<div wire:poll.1s="tick" class="py-10 max-w-5xl mx-auto px-4">

    {{-- ══════════════════════ KUIS BELUM DIMULAI ══════════════════════ --}}
    @if($quizNotStarted)
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="bg-white border border-slate-100 rounded-3xl p-10 shadow-xl text-center space-y-6 max-w-md mx-auto">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Kuis Belum Dimulai</h1>
                    <p class="text-slate-500 text-sm mt-2">
                        {{ $activeQuiz?->name ?? 'Lomba Cerdas Cermat' }} akan dimulai pada:
                    </p>
                    @if($activeQuiz?->start_at)
                        <p class="text-red-600 font-extrabold text-lg mt-1">
                            {{ $activeQuiz->start_at->format('d M Y · H:i') }} WIT
                        </p>
                    @endif
                </div>

                {{-- Countdown display --}}
                @php
                    $h = intdiv($secondsToStart, 3600);
                    $m = intdiv($secondsToStart % 3600, 60);
                    $s = $secondsToStart % 60;
                @endphp
                <div class="flex justify-center gap-3">
                    @foreach([['val' => $h, 'label' => 'Jam'], ['val' => $m, 'label' => 'Menit'], ['val' => $s, 'label' => 'Detik']] as $unit)
                        <div class="bg-slate-800 text-white rounded-2xl px-4 py-3 min-w-[70px] text-center shadow-lg">
                            <div class="text-3xl font-black tabular-nums">{{ str_pad($unit['val'], 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-slate-400 text-[10px] uppercase tracking-wider mt-0.5">{{ $unit['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <p class="text-slate-400 text-xs">Halaman ini akan otomatis terbuka saat kuis dimulai.</p>
            </div>
        </div>

    {{-- ══════════════════════ KUIS SUDAH SELESAI ══════════════════════ --}}
    @elseif($quizEnded && !$finished)
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="bg-white border border-slate-100 rounded-3xl p-10 shadow-xl text-center space-y-4 max-w-md mx-auto">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21v11h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                </div>
                <h1 class="text-2xl font-black text-slate-800">Waktu Kuis Sudah Habis</h1>
                <p class="text-slate-500 text-sm">Periode pengerjaan kuis telah berakhir. Jawaban Anda sudah otomatis tersimpan.</p>
                <a href="{{ route('public.quiz.leaderboard') }}" class="btn btn-primary inline-flex items-center gap-1.5 mt-4">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
                    Lihat Leaderboard
                </a>
            </div>
        </div>

    {{-- ══════════════════════ HASIL KUIS ══════════════════════ --}}
    @elseif($finished)
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xl text-center space-y-6 max-w-lg mx-auto">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl shadow">
                <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800">Kuis Selesai!</h1>
                <p class="text-xs text-slate-500 mt-1">Terima kasih telah berpartisipasi dalam Lomba Cerdas Cermat 17 Agustus 2026.</p>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-3 text-left">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Nama Peserta</span>
                    <span class="font-bold text-slate-800">{{ $registration->name }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Jawaban Benar</span>
                    <span class="font-bold text-slate-800">{{ $correctCount }} / {{ $questions?->count() ?? 0 }} Soal</span>
                </div>
                <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                    <span class="text-slate-600 font-bold">Total Skor</span>
                    <span class="text-xl font-black text-red-600">{{ $score }} Poin</span>
                </div>
                @if($registration->finished_at)
                    <div class="text-[11px] text-slate-400 text-right">
                        Selesai: {{ $registration->finished_at->format('d M Y H:i:s') }} WIT
                    </div>
                @endif
            </div>

            <div class="space-y-2 pt-2">
                <a href="{{ route('public.quiz.leaderboard') }}" class="btn btn-primary w-full py-2.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
                    Lihat Papan Peringkat
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="w-full text-slate-400 hover:text-slate-600 font-bold text-xs py-2">Keluar Portal</button>
                </form>
            </div>
        </div>

    {{-- ══════════════════════ SEDANG MENGERJAKAN ══════════════════════ --}}
    @else
        {{-- Submit Confirmation Modal --}}
        @if($confirmingSubmit)
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4">
                    <h3 class="text-lg font-bold text-slate-800">Selesaikan Kuis?</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Jawaban Anda akan dikunci dan tidak bisa diubah lagi.</p>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                        <span class="font-bold text-slate-700">Kemajuan:</span>
                        {{ count($answers) }} dari {{ $questions->count() }} soal dijawab.
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="cancelSubmit" class="btn btn-secondary btn-sm">Batal</button>
                        <button type="button" wire:click="submitQuiz" class="btn btn-primary btn-sm bg-red-600 hover:bg-red-700 text-white flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesai &amp; Kirim
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ── Question Panel ── --}}
            <div class="lg:col-span-8 space-y-4">
                @if($questions && $questions->count() > 0)
                    @php
                        $q        = $questions[$currentIndex];
                        $selected = $answers[$q->id] ?? null;
                        $limit    = $q->effective_time_limit;
                    @endphp

                    {{-- Countdown Timer --}}
                    @if($limit !== null)
                        @php
                            $pct      = $limit > 0 ? round(($secondsLeft / $limit) * 100) : 0;
                            $urgent   = $secondsLeft <= 10;
                            $circumf  = 2 * M_PI * 28; // text radius
                            $dashArr  = round($circumf * ($pct / 100), 2);
                        @endphp
                        <div class="flex items-center justify-between px-5 py-3 rounded-2xl border
                            {{ $urgent ? 'bg-red-50 border-red-200' : 'bg-white border-slate-200' }} shadow-sm">
                            <div class="text-sm font-bold flex items-center gap-1.5 {{ $urgent ? 'text-red-600 animate-pulse' : 'text-slate-600' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Waktu per soal
                            </div>
                            <div class="flex items-center gap-3">
                                {{-- SVG circular progress --}}
                                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 64 64">
                                    <circle cx="32" cy="32" r="28" fill="none"
                                            stroke="{{ $urgent ? '#fee2e2' : '#e2e8f0' }}" stroke-width="6"/>
                                    <circle cx="32" cy="32" r="28" fill="none"
                                            stroke="{{ $urgent ? '#dc2626' : '#2563eb' }}" stroke-width="6"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ $dashArr }} {{ round($circumf, 2) }}"
                                            style="transition: stroke-dasharray 0.8s ease"/>
                                    <text x="32" y="37" text-anchor="middle" class="rotate-90"
                                          style="transform-origin:32px 32px;transform:rotate(90deg);
                                                 font-size:14px;font-weight:900;
                                                 fill:{{ $urgent ? '#dc2626' : '#1e40af' }}">
                                        {{ $secondsLeft }}
                                    </text>
                                </svg>
                                <div class="text-right">
                                    <div class="text-2xl font-black tabular-nums {{ $urgent ? 'text-red-600' : 'text-slate-800' }}">
                                        {{ $secondsLeft }}<span class="text-xs font-normal">d</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400">dari {{ $limit }}d</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Quiz Info Bar --}}
                    @if($activeQuiz)
                        <div class="flex items-center justify-between px-4 py-2 bg-red-600/5 border border-red-100 rounded-xl text-xs">
                            <span class="font-bold text-red-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
                                {{ $activeQuiz->name }}
                            </span>
                            @if($activeQuiz->end_at)
                                @php $rem = $activeQuiz->secondsUntilEnd(); @endphp
                                <span class="text-slate-500">
                                    Berakhir: <strong class="text-slate-700">{{ $activeQuiz->end_at->format('H:i') }} WIT</strong>
                                    @if($rem !== null && $rem < 600)
                                        <span class="text-red-500 font-bold animate-pulse ml-1">({{ gmdate('i:s', $rem) }})</span>
                                    @endif
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Question Card --}}
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                Soal No. {{ $currentIndex + 1 }} dari {{ $questions->count() }}
                            </span>
                            <span class="bg-blue-50 text-blue-700 font-bold text-[10px] px-2 py-0.5 rounded-full">
                                {{ $q->points }} Poin
                            </span>
                        </div>

                        <div class="text-slate-800 text-base font-semibold leading-relaxed whitespace-pre-line">
                            {{ $q->question }}
                        </div>

                        @if($q->image_path)
                            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 p-2 max-w-lg">
                                <img src="{{ asset('storage/' . $q->image_path) }}"
                                     alt="Soal {{ $currentIndex + 1 }}"
                                     class="w-full h-auto object-contain max-h-72 rounded-xl">
                            </div>
                        @endif

                        {{-- Options --}}
                        <div class="space-y-3">
                            @foreach(['a','b','c','d'] as $opt)
                                <button type="button"
                                        wire:click="selectAnswer({{ $q->id }}, '{{ $opt }}')"
                                        class="w-full text-left p-4 rounded-2xl border transition-all flex items-center gap-3
                                            {{ $selected === $opt
                                                ? 'border-red-600 bg-red-50/50 text-red-950 font-bold'
                                                : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                        {{ $selected === $opt ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                                        {{ strtoupper($opt) }}
                                    </span>
                                    <span>{{ $q->{'option_'.$opt} }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Navigation --}}
                        <div class="flex justify-between items-center border-t border-slate-100 pt-6">
                            <button type="button" wire:click="prevQuestion"
                                    class="btn btn-secondary btn-sm" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                                ← Sebelumnya
                            </button>
                            @if($currentIndex === $questions->count() - 1)
                                <button type="button" wire:click="startSubmit"
                                        class="btn btn-primary btn-sm bg-red-600 hover:bg-red-700 text-white">
                                    Kirim Jawaban Akhir
                                </button>
                            @else
                                <button type="button" wire:click="nextQuestion" class="btn btn-secondary btn-sm">
                                    Berikutnya →
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center text-slate-400">
                        Belum ada soal yang aktif untuk kuis ini.
                    </div>
                @endif
            </div>

            {{-- ── Sidebar: Progress & Jump Grid ── --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5 sticky top-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Status Jawaban</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Setiap jawaban tersimpan otomatis.</p>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                        $total    = $questions ? $questions->count() : 0;
                        $progress = $total > 0 ? (count($answers) / $total) * 100 : 0;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold text-slate-600">
                            <span>Dijawab</span>
                            <span>{{ count($answers) }} / {{ $total }} Soal</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5">
                            <div class="bg-red-600 h-2.5 rounded-full transition-all duration-500"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    {{-- Jump Grid --}}
                    @if($questions)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($questions as $idx => $quest)
                                @php $hasAnswered = isset($answers[$quest->id]); @endphp
                                <button type="button" wire:click="selectIndex({{ $idx }})"
                                        class="w-full aspect-square rounded-xl border flex items-center justify-center text-xs font-bold transition-all
                                            {{ $currentIndex === $idx
                                                ? 'border-red-600 bg-red-50 text-red-600 ring-2 ring-red-100'
                                                : ($hasAnswered
                                                    ? 'border-emerald-500 bg-emerald-50 text-emerald-700'
                                                    : 'border-slate-200 hover:bg-slate-50 text-slate-500') }}">
                                    {{ $idx + 1 }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-100 space-y-2">
                        <button type="button" wire:click="startSubmit"
                                class="btn btn-primary w-full py-2 bg-red-600 hover:bg-red-700 text-white flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Jawaban Akhir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
