<div>
    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-8z"/></svg>
                Kelola Sesi Kuis
            </h1>
            <p class="text-slate-500 text-xs mt-0.5">Buat dan atur sesi lomba cerdas cermat 17 Agustus</p>
        </div>
        <button wire:click="create"
                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-4 py-2 rounded-xl shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Kuis Baru
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama kuis…"
               class="w-full sm:w-72 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
    </div>

    {{-- Quiz Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($quizzes as $quiz)
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                {{-- Status stripe --}}
                <div class="h-1.5 w-full
                    {{ $quiz->status === 'active' ? 'bg-green-400' : ($quiz->status === 'finished' ? 'bg-slate-300' : 'bg-amber-400') }}">
                </div>

                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <h2 class="font-extrabold text-slate-800 leading-tight">{{ $quiz->name }}</h2>
                            @if($quiz->description)
                                <p class="text-slate-400 text-xs mt-1 line-clamp-2">{{ $quiz->description }}</p>
                            @endif
                        </div>
                        <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $quiz->status === 'active' ? 'bg-green-100 text-green-700' : ($quiz->status === 'finished' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-700') }}">
                            {{ $quiz->status_label }}
                        </span>
                    </div>

                    {{-- Meta info --}}
                    <div class="space-y-1.5 text-xs text-slate-500 mb-4">
                        @if($quiz->start_at)
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <span>Mulai: <strong class="text-slate-700">{{ $quiz->start_at->format('d M Y · H:i') }} WIT</strong></span>
                            </div>
                        @endif
                        @if($quiz->end_at)
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21v11h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                </span>
                                <span>Selesai: <strong class="text-slate-700">{{ $quiz->end_at->format('d M Y · H:i') }} WIT</strong></span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-slate-300">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span>Durasi: <strong class="text-slate-700">{{ $quiz->duration_minutes }} menit</strong>
                            @if($quiz->time_per_question)
                                · Batas per soal: <strong class="text-slate-700">{{ $quiz->time_per_question }} dtk</strong>
                            @else
                                · Per soal: <em>tak terbatas</em>
                            @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-2 pt-2 border-t border-slate-100">
                            <div class="text-center">
                                <div class="font-black text-slate-700 text-sm">{{ $quiz->questions_count }}</div>
                                <div class="text-[10px] text-slate-400">Soal</div>
                            </div>
                            <div class="text-center">
                                <div class="font-black text-slate-700 text-sm">{{ $quiz->registrations_count }}</div>
                                <div class="text-[10px] text-slate-400">Peserta</div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <button wire:click="edit({{ $quiz->id }})"
                                class="flex-1 text-center text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-lg transition flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Edit
                        </button>
                        <button wire:click="toggleStatus({{ $quiz->id }})"
                                wire:confirm="Ubah status kuis ini?"
                                class="flex-1 text-center text-xs font-bold px-3 py-2 rounded-lg transition flex items-center justify-center gap-1
                                {{ $quiz->status === 'draft' ? 'bg-green-100 hover:bg-green-200 text-green-700' : ($quiz->status === 'active' ? 'bg-slate-100 hover:bg-slate-200 text-slate-600' : 'bg-amber-100 hover:bg-amber-200 text-amber-700') }}">
                            @if($quiz->status === 'draft')
                                <svg class="w-3.5 h-3.5 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                <span>Aktifkan</span>
                            @elseif($quiz->status === 'active')
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                <span>Selesaikan</span>
                            @else
                                <svg class="w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Draft lagi</span>
                            @endif
                        </button>
                        @if($quiz->registrations_count === 0)
                            <button wire:click="delete({{ $quiz->id }})"
                                    wire:confirm="Hapus kuis ini?"
                                    class="text-xs font-bold bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg transition flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="font-semibold">Belum ada kuis. Klik "Buat Kuis Baru" untuk memulai.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════ MODAL FORM ═══════════════ --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">{{ $editingId ? 'Edit Kuis' : 'Buat Kuis Baru' }}</h2>
                <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">×</button>
            </div>

            <div class="p-6 space-y-4">
                {{-- Name --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Kuis <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="Kuis Cerdas Cermat 17 Agustus 2026"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="2" placeholder="Deskripsi singkat…"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                </div>

                {{-- Start & End --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Waktu Mulai <span class="text-slate-400">(WIT)</span></label>
                        <input wire:model="start_at" type="datetime-local"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('start_at') border-red-400 @enderror">
                        @error('start_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Waktu Selesai <span class="text-slate-400">(WIT)</span></label>
                        <input wire:model="end_at" type="datetime-local"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 @error('end_at') border-red-400 @enderror">
                        @error('end_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Duration & Time per question --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Durasi Total (menit) <span class="text-red-500">*</span></label>
                        <input wire:model="duration_minutes" type="number" min="1" max="480"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('duration_minutes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Batas Waktu per Soal (detik)</label>
                        <input wire:model="time_per_question" type="number" min="5" max="600" placeholder="Kosong = tak terbatas"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('time_per_question') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select wire:model="status"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="draft">Draft (belum dibuka)</option>
                        <option value="active">Aktif (peserta bisa mengerjakan)</option>
                        <option value="finished">Selesai</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 pb-5">
                <button wire:click="$set('showForm', false)"
                        class="text-sm font-bold text-slate-500 hover:text-slate-700 px-4 py-2">Batal</button>
                <button wire:click="save"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-6 py-2 rounded-xl shadow transition">
                    {{ $editingId ? 'Simpan Perubahan' : 'Buat Kuis' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
