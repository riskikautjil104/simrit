<div>
    @if($isEditing || $isCreating)
        {{-- ═══════════════════════════════════ FORM ═══════════════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Soal Baru' : 'Edit Soal' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                {{-- ── Pilih Kuis ── --}}
                <div>
                    <label class="form-label font-bold">Masukkan ke Kuis <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <select wire:model.defer="quiz_id" class="form-select">
                        <option value="">— Tidak terikat kuis tertentu —</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">
                                {{ $quiz->name }}
                                ({{ $quiz->status_label }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ── Teks Soal ── --}}
                <div>
                    <label for="q-text" class="form-label">Pertanyaan Soal</label>
                    <textarea id="q-text" wire:model.defer="question" rows="4"
                              class="form-textarea @error('question') is-error @enderror"
                              placeholder="Tuliskan pertanyaan disini..."></textarea>
                    @error('question') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- ── Gambar Soal ── --}}
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <label for="q-image" class="form-label font-bold text-slate-700">Gambar Soal (Opsional)</label>
                    @if($image)
                        <div class="mb-3">
                            <span class="text-xs text-slate-400 block mb-1">Preview Gambar Baru:</span>
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded-lg object-contain border border-slate-200 bg-white">
                        </div>
                    @elseif($existingImage)
                        <div class="mb-3">
                            <span class="text-xs text-slate-400 block mb-1">Gambar Saat Ini:</span>
                            <img src="{{ asset('storage/' . $existingImage) }}" alt="Existing" class="max-h-48 rounded-lg object-contain border border-slate-200 bg-white">
                        </div>
                    @endif
                    <input type="file" id="q-image" wire:model="image" accept="image/*" class="form-input text-xs">
                    <p class="form-hint text-[10px] mt-1">Format: JPG, PNG, WEBP. Maks 5MB.</p>
                    @error('image') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- ── Pilihan A–D ── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['a','b','c','d'] as $opt)
                        <div>
                            <label for="opt-{{ $opt }}" class="form-label">Pilihan {{ strtoupper($opt) }}</label>
                            <input type="text" id="opt-{{ $opt }}" wire:model.defer="option_{{ $opt }}"
                                   class="form-input @error('option_'.$opt) is-error @enderror"
                                   placeholder="Jawaban {{ strtoupper($opt) }}">
                            @error('option_'.$opt) <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>

                {{-- ── Meta: kunci, poin, status, urutan, waktu ── --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <label for="q-correct" class="form-label">Jawaban Benar</label>
                        <select id="q-correct" wire:model.defer="correct_answer"
                                class="form-select @error('correct_answer') is-error @enderror">
                            <option value="a">Pilihan A</option>
                            <option value="b">Pilihan B</option>
                            <option value="c">Pilihan C</option>
                            <option value="d">Pilihan D</option>
                        </select>
                    </div>
                    <div>
                        <label for="q-points" class="form-label">Poin</label>
                        <input type="number" id="q-points" wire:model.defer="points"
                               class="form-input @error('points') is-error @enderror" min="1">
                    </div>
                    <div>
                        <label for="q-status" class="form-label">Status</label>
                        <select id="q-status" wire:model.defer="status" class="form-select">
                            <option value="active">Aktif</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div>
                        <label for="q-sort" class="form-label">Urutan</label>
                        <input type="number" id="q-sort" wire:model.defer="sort_order"
                               class="form-input @error('sort_order') is-error @enderror" min="0">
                    </div>
                    <div>
                        <label for="q-time" class="form-label">
                            Waktu (detik)
                            <span class="text-[10px] text-slate-400 font-normal block">kosong = pakai default kuis</span>
                        </label>
                        <input type="number" id="q-time" wire:model.defer="time_limit"
                               class="form-input @error('time_limit') is-error @enderror"
                               min="5" max="600" placeholder="mis: 60">
                        @error('time_limit') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Soal</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- ═══════════════════════════════════ LIST ═══════════════════════════════════ --}}
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l4 4L19 6"/></svg> {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            {{-- Header controls --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    {{-- Filter kuis --}}
                    <select wire:model.live="filterQuizId" class="form-select text-sm w-full sm:w-56">
                        <option value="">Semua soal</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->name }}</option>
                        @endforeach
                    </select>
                    {{-- Search --}}
                    <div class="relative w-full sm:w-64">
                        <input type="text" wire:model.live.debounce.300ms="search"
                               class="form-input pl-8 py-1.5 text-sm" placeholder="Cari pertanyaan...">
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto flex items-center justify-center gap-1">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Soal
                </button>
            </div>

            {{-- Info banner jika filter kuis aktif --}}
            @if($filterQuizId)
                @php $selectedQuiz = $quizzes->firstWhere('id', $filterQuizId); @endphp
                @if($selectedQuiz)
                    <div class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 border border-blue-100 rounded-xl text-sm">
                        <span class="text-blue-600 font-bold flex items-center gap-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Kuis:
                        </span>
                        <span class="text-blue-800 font-semibold">{{ $selectedQuiz->name }}</span>
                        <span class="text-blue-400 text-xs flex items-center gap-3">
                            <span>Mulai: {{ $selectedQuiz->start_at ? $selectedQuiz->start_at->format('d M Y H:i').' WIT' : 'Belum ada jadwal' }}</span>
                            @if($selectedQuiz->time_per_question)
                                <span>· Default {{ $selectedQuiz->time_per_question }} dtk/soal</span>
                            @endif
                        </span>
                    </div>
                @endif
            @endif

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col" class="w-12 text-center">No</th>
                                <th scope="col">Pertanyaan Soal</th>
                                <th scope="col" class="hidden md:table-cell">Kuis</th>
                                <th scope="col">Kunci</th>
                                <th scope="col">Poin</th>
                                <th scope="col" class="hidden sm:table-cell flex items-center gap-1 justify-center py-3">Waktu</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($questions as $q)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="text-center font-mono text-xs">
                                        <div class="text-slate-700 font-bold">#{{ $q->sort_order }}</div>
                                        <div class="flex justify-center gap-1 mt-1">
                                            <button type="button" wire:click="reorder({{ $q->id }}, 'up')"
                                                    class="text-[10px] text-slate-400 hover:text-slate-700">▲</button>
                                            <button type="button" wire:click="reorder({{ $q->id }}, 'down')"
                                                    class="text-[10px] text-slate-400 hover:text-slate-700">▼</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-start gap-3">
                                            @if($q->image_path)
                                                <img src="{{ asset('storage/' . $q->image_path) }}" alt=""
                                                     class="w-10 h-10 object-cover rounded-lg border border-slate-200 flex-shrink-0">
                                            @endif
                                            <div class="min-w-0">
                                                <div class="text-slate-800 text-sm font-semibold line-clamp-2">{{ $q->question }}</div>
                                                <div class="grid grid-cols-2 gap-x-3 mt-1 text-[11px] text-slate-400">
                                                    <div><span class="font-bold text-slate-500">A:</span> {{ Str::limit($q->option_a, 30) }}</div>
                                                    <div><span class="font-bold text-slate-500">B:</span> {{ Str::limit($q->option_b, 30) }}</div>
                                                    <div><span class="font-bold text-slate-500">C:</span> {{ Str::limit($q->option_c, 30) }}</div>
                                                    <div><span class="font-bold text-slate-500">D:</span> {{ Str::limit($q->option_d, 30) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell text-xs text-slate-500">
                                        @if($q->quiz)
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full font-semibold">
                                                {{ Str::limit($q->quiz->name, 25) }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-emerald-50 text-emerald-700 font-bold uppercase">
                                            {{ strtoupper($q->correct_answer) }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-600 font-bold">{{ $q->points }} Pts</td>
                                    <td class="hidden sm:table-cell text-xs">
                                        @if($q->time_limit)
                                            <span class="text-amber-600 font-bold">{{ $q->time_limit }}d</span>
                                        @elseif($q->quiz?->time_per_question)
                                            <span class="text-slate-400">{{ $q->quiz->time_per_question }}d</span>
                                        @else
                                            <span class="text-slate-300">∞</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $q->status === 'active' ? 'published' : 'draft' }}">
                                            {{ $q->status === 'active' ? 'Aktif' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $q->id }})"
                                                    class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button"
                                                    wire:click="delete({{ $q->id }})"
                                                    wire:confirm="Hapus soal ini?"
                                                    class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">
                                        @if($filterQuizId)
                                            Belum ada soal untuk kuis ini. Klik "Tambah Soal" untuk memulai.
                                        @else
                                            Belum ada soal yang dibuat.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $questions->links() }}</div>
        </div>
    @endif
</div>
