<div>
    {{-- Alert/Modal to display generated password once --}}
    @if($generatedPassword)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12l4 4L19 6"/></svg></div>
                    <h3 class="text-lg font-bold text-slate-800">Akun Peserta Berhasil Dibuat</h3>
                    <p class="text-xs text-slate-500 mt-1">Harap salin informasi kredensial login ini dan kirimkan ke peserta. Password ini hanya muncul sekali.</p>
                </div>
                <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100 font-mono text-xs">
                    <div>
                        <span class="text-slate-400">Nama:</span>
                        <span class="text-slate-800 font-bold ml-1">{{ $generatedName }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400">Email:</span>
                        <span class="text-slate-800 font-bold ml-1">{{ $generatedEmail }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-slate-400">Password:</span>
                            <span class="text-emerald-700 font-black ml-1 text-sm bg-emerald-50 px-1.5 py-0.5 rounded">{{ $generatedPassword }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <button type="button" wire:click="closeCredentials" class="btn btn-primary w-full">Selesai & Salin</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Rejection Dialog/Modal --}}
    @if($rejectingId)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-40 animate-fade-in">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 max-w-md w-full shadow-xl">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Tolak Pendaftaran</h3>
                <form wire:submit.prevent="submitReject" class="space-y-4">
                    <div>
                        <label for="rej-reason" class="form-label">Alasan Penolakan</label>
                        <textarea id="rej-reason" wire:model.defer="rejection_reason" rows="4" class="form-textarea @error('rejection_reason') is-error @enderror" placeholder="Isi alasan penolakan pendaftaran lomba..."></textarea>
                        @error('rejection_reason') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="cancelReject" class="btn btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm">Tolak Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Main view content --}}
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari nama, email, jabatan...">
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
                <select wire:model.live="statusFilter" class="form-select py-1.5 text-sm w-40">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr>
                            <th scope="col">Nama / Jabatan</th>
                            <th scope="col">Kontak</th>
                            <th scope="col">Status</th>
                            <th scope="col">Terdaftar Pada</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($registrations as $reg)
                            <tr>
                                <td>
                                    <div class="font-bold text-slate-800 text-sm">{{ $reg->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $reg->position }}</div>
                                </td>
                                <td>
                                    <div class="text-xs text-slate-700">{{ $reg->email }}</div>
                                    <div class="text-xs text-slate-400">{{ $reg->phone }}</div>
                                </td>
                                <td>
                                    @if($reg->status === 'approved')
                                        <span class="badge badge-published">Disetujui</span>
                                    @elseif($reg->status === 'rejected')
                                        <span class="badge badge-archived" title="Alasan: {{ $reg->rejection_reason }}">Ditolak</span>
                                    @else
                                        <span class="badge badge-draft">Pending</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $reg->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end items-center gap-1.5 flex-wrap">
                                        <button type="button" wire:click="showDetails({{ $reg->id }})" class="btn btn-sm btn-ghost flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </button>
                                        
                                        @if($reg->status === 'pending')
                                            <button type="button" wire:click="approve({{ $reg->id }})" class="btn btn-sm btn-success">Setujui</button>
                                            <button type="button" wire:click="startReject({{ $reg->id }})" class="btn btn-sm btn-danger">Tolak</button>
                                        @elseif($reg->status === 'approved' && $reg->user_id)
                                            <button type="button" wire:click="viewAnswers({{ $reg->user_id }}, '{{ addslashes($reg->name) }}')" class="btn btn-sm btn-ghost flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Jawaban
                                            </button>
                                            @if($reg->user)
                                                <button type="button" wire:click="toggleParticipantActive({{ $reg->id }})" class="btn btn-sm {{ $reg->user->is_active ? 'btn-danger' : 'btn-success' }}">
                                                    {{ $reg->user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-slate-400">Belum ada pendaftar lomba.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>{{ $registrations->links() }}</div>
    </div>

    {{-- Detailed Answers Modal --}}
    @if($viewingAnswersUserId)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-40 animate-fade-in">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 max-w-2xl w-full shadow-2xl flex flex-col max-h-[85vh]">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4 flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Detail Jawaban Kuis</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Peserta: <strong class="text-slate-700">{{ $viewingParticipantName }}</strong></p>
                    </div>
                    <button type="button" wire:click="closeAnswers" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
                </div>
                
                <div class="flex-1 overflow-y-auto pr-1 space-y-5">
                    @forelse($participantAnswers as $index => $ans)
                        <div class="p-4 rounded-xl border {{ $ans['selected_answer'] === null ? 'border-slate-200 bg-slate-50' : ($ans['is_correct'] ? 'border-emerald-100 bg-emerald-50/30' : 'border-rose-100 bg-rose-50/30') }}">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <span class="font-bold text-slate-800 text-sm">Soal #{{ $index + 1 }}</span>
                                @if($ans['selected_answer'] === null)
                                    <span class="badge badge-draft text-[10px]">Belum Dijawab</span>
                                @elseif($ans['is_correct'])
                                    <span class="badge badge-published text-[10px] bg-emerald-100 text-emerald-800 border-none">✓ Benar</span>
                                @else
                                    <span class="badge badge-archived text-[10px] bg-red-100 text-red-800 border-none">✕ Salah</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-slate-700 font-semibold mb-3 leading-relaxed">{{ $ans['question'] }}</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded border {{ $ans['selected_answer'] === 'a' ? 'border-blue-300 bg-blue-50/50' : 'border-slate-100' }} {{ $ans['correct_answer'] === 'a' ? 'ring-2 ring-emerald-500 font-bold' : '' }}">
                                    <strong>A:</strong> {{ $ans['option_a'] }}
                                </div>
                                <div class="p-2 rounded border {{ $ans['selected_answer'] === 'b' ? 'border-blue-300 bg-blue-50/50' : 'border-slate-100' }} {{ $ans['correct_answer'] === 'b' ? 'ring-2 ring-emerald-500 font-bold' : '' }}">
                                    <strong>B:</strong> {{ $ans['option_b'] }}
                                </div>
                                <div class="p-2 rounded border {{ $ans['selected_answer'] === 'c' ? 'border-blue-300 bg-blue-50/50' : 'border-slate-100' }} {{ $ans['correct_answer'] === 'c' ? 'ring-2 ring-emerald-500 font-bold' : '' }}">
                                    <strong>C:</strong> {{ $ans['option_c'] }}
                                </div>
                                <div class="p-2 rounded border {{ $ans['selected_answer'] === 'd' ? 'border-blue-300 bg-blue-50/50' : 'border-slate-100' }} {{ $ans['correct_answer'] === 'd' ? 'ring-2 ring-emerald-500 font-bold' : '' }}">
                                    <strong>D:</strong> {{ $ans['option_d'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 text-xs py-8">Tidak ada soal cerdas cermat.</p>
                    @endforelse
                </div>
                
                <div class="mt-6 border-t border-slate-100 pt-4 flex justify-end flex-shrink-0">
                    <button type="button" wire:click="closeAnswers" class="btn btn-secondary btn-sm">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Participant Details Modal --}}
    @if($viewingRegDetails)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-40 animate-fade-in">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-lg font-bold text-slate-800">Detail Pendaftar Lomba</h3>
                    <button type="button" wire:click="closeDetails" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>
                
                <div class="space-y-3.5 text-xs">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">Nama Lengkap</span>
                        <span class="col-span-2 font-bold text-slate-800">{{ $viewingRegDetails->name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">Jabatan / Instansi</span>
                        <span class="col-span-2 font-bold text-slate-700">{{ $viewingRegDetails->position }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">Email</span>
                        <span class="col-span-2 font-bold text-slate-700">{{ $viewingRegDetails->email }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">No. WhatsApp</span>
                        <span class="col-span-2 font-bold text-slate-700">{{ $viewingRegDetails->phone }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">Tanggal Daftar</span>
                        <span class="col-span-2 text-slate-600">{{ $viewingRegDetails->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400">Status Pendaftaran</span>
                        <span class="col-span-2">
                            @if($viewingRegDetails->status === 'approved')
                                <span class="badge badge-published">Disetujui</span>
                            @elseif($viewingRegDetails->status === 'rejected')
                                <span class="badge badge-archived">Ditolak</span>
                            @else
                                <span class="badge badge-draft">Pending</span>
                            @endif
                        </span>
                    </div>
                    
                    @if($viewingRegDetails->rejection_reason)
                        <div class="bg-red-50 p-3 rounded-lg border border-red-100 text-red-800">
                            <strong>Alasan Penolakan:</strong> {{ $viewingRegDetails->rejection_reason }}
                        </div>
                    @endif
                    
                    @if($viewingRegDetails->user_id && $viewingRegDetails->user)
                        <div class="border-t border-slate-100 pt-3 space-y-2">
                            <h4 class="font-bold text-slate-700">Informasi Akun Login</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <span class="text-slate-400">Status Akun</span>
                                <span class="col-span-2">
                                    <span class="badge {{ $viewingRegDetails->user->is_active ? 'badge-published' : 'badge-archived' }}">
                                        {{ $viewingRegDetails->user->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <span class="text-slate-400">Selesai Ujian</span>
                                <span class="col-span-2 font-bold text-slate-700">
                                    {{ $viewingRegDetails->finished_at ? $viewingRegDetails->finished_at->format('d M Y H:i') : 'Belum Selesai' }}
                                </span>
                            </div>
                            
                            <div class="pt-2">
                                <button type="button" wire:click="toggleParticipantActive({{ $viewingRegDetails->id }})" class="btn w-full btn-sm {{ $viewingRegDetails->user->is_active ? 'btn-danger' : 'btn-success' }}">
                                    {{ $viewingRegDetails->user->is_active ? 'Nonaktifkan Akun Login' : 'Aktifkan Akun Login' }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-4 flex justify-end">
                    <button type="button" wire:click="closeDetails" class="btn btn-secondary btn-sm">Tutup</button>
                </div>
            </div>
        </div>
    @endif
</div>
