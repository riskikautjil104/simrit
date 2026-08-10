<div>
    @if($isEditing || $isCreating)
        {{-- Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Buat Agenda Baru' : 'Edit Agenda' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    {{-- Form fields --}}
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="event-title" class="form-label">Nama Kegiatan / Agenda</label>
                            <input type="text" id="event-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Ketik nama kegiatan...">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="event-start" class="form-label">Mulai Pada</label>
                                <input type="datetime-local" id="event-start" wire:model.defer="starts_at" class="form-input @error('starts_at') is-error @enderror">
                                @error('starts_at') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="event-end" class="form-label">Selesai Pada (Opsional)</label>
                                <input type="datetime-local" id="event-end" wire:model.defer="ends_at" class="form-input @error('ends_at') is-error @enderror">
                                @error('ends_at') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="event-loc" class="form-label">Lokasi / Tempat</label>
                            <input type="text" id="event-loc" wire:model.defer="location" class="form-input @error('location') is-error @enderror" placeholder="e.g. Aula RSUD, Ruang IT Lt. 2...">
                            @error('location') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="event-desc" class="form-label">Deskripsi Agenda (HTML didukung)</label>
                            <textarea id="event-desc" wire:model.defer="description" rows="8" class="form-textarea @error('description') is-error @enderror font-mono text-sm" placeholder="Detail agenda kegiatan..."></textarea>
                            @error('description') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Cover & Status --}}
                    <div class="md:col-span-4 space-y-5">
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="event-status" class="form-label">Status Penerbitan</label>
                            <select id="event-status" wire:model.defer="status" class="form-select @error('status') is-error @enderror">
                                <option value="draft">Draft (Sembunyikan)</option>
                                <option value="published">Diterbitkan (Publik)</option>
                                <option value="archived">Diarsipkan</option>
                            </select>
                            @error('status') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="cover-image" class="form-label">Gambar Banner/Pamflet (Featured Image)</label>
                            @if($cover_image)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="object-cover w-full h-full">
                                </div>
                            @elseif($existingCoverImage)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ asset('storage/'.$existingCoverImage) }}" alt="Banner" class="object-cover w-full h-full">
                                </div>
                            @endif

                            <input type="file" id="cover-image" wire:model="cover_image" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Format: JPG, PNG, WEBP. Maksimal 5MB.</p>
                            @error('cover_image') <p class="form-error text-[10px]">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Agenda</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- List --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-64">
                    <label for="search-event" class="sr-only">Cari judul...</label>
                    <input type="text" id="search-event" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari agenda / lokasi...">
                </div>

                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tambah Agenda
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Nama Agenda</th>
                                <th scope="col">Waktu Mulai</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($events as $ev)
                                <tr>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $ev->title }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Oleh: {{ $ev->creator ? $ev->creator->name : 'Sistem' }}</div>
                                    </td>
                                    <td class="text-xs text-slate-600">
                                        {{ $ev->starts_at->format('d M Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="text-xs text-slate-600 font-medium">
                                            {{ $ev->location ?: 'Internal IT' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ev->status }}">{{ $ev->status }}</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $ev->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Apakah Anda yakin ingin menghapus agenda ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $ev->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 empty-state">
                                        <svg class="w-10 h-10 mx-auto opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <h3 class="mt-2">Belum ada agenda</h3>
                                        <p>Silakan klik tombol "Tambah Agenda" di atas untuk menambahkan jadwal kegiatan baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $events->links() }}
            </div>
        </div>
    @endif
</div>
