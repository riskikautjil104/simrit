<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Video Baru' : 'Edit Video' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label for="vid-title" class="form-label">Judul Video</label>
                    <input type="text" id="vid-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Judul video kegiatan...">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="vid-url" class="form-label">URL Embed Video (YouTube / Vimeo)</label>
                    <input type="url" id="vid-url" wire:model.defer="embed_url" class="form-input @error('embed_url') is-error @enderror" placeholder="https://www.youtube.com/watch?v=...">
                    <p class="form-hint">Tempel URL video YouTube atau Vimeo untuk disematkan di halaman publik.</p>
                    @error('embed_url') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="vid-desc" class="form-label">Deskripsi (Opsional)</label>
                    <textarea id="vid-desc" wire:model.defer="description" rows="3" class="form-textarea" placeholder="Keterangan singkat video..."></textarea>
                </div>
                <div>
                    <label for="vid-status" class="form-label">Status</label>
                    <select id="vid-status" wire:model.defer="status" class="form-select">
                        <option value="published">Diterbitkan</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Diarsipkan</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Video</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari judul video...">
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tambah Video</button>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Judul Video</th>
                                <th scope="col">URL Embed</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($videos as $v)
                                <tr>
                                    <td class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $v->title }}</td>
                                    <td class="text-xs text-slate-500 truncate max-w-[180px]">{{ $v->embed_url }}</td>
                                    <td><span class="badge badge-{{ $v->status }}">{{ $v->status }}</span></td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $v->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Hapus video ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $v->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-12 text-center text-slate-400">Belum ada video ditambahkan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $videos->links() }}</div>
        </div>
    @endif
</div>
