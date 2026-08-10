<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Buat Pengumuman Baru' : 'Edit Pengumuman' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label for="ann-title" class="form-label">Judul Pengumuman</label>
                    <input type="text" id="ann-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Judul pengumuman resmi...">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ann-date" class="form-label">Tanggal Publikasi (Opsional)</label>
                    <input type="date" id="ann-date" wire:model.defer="published_at" class="form-input @error('published_at') is-error @enderror">
                    @error('published_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ann-status" class="form-label">Status</label>
                    <select id="ann-status" wire:model.defer="status" class="form-select @error('status') is-error @enderror">
                        <option value="draft">Draft</option>
                        <option value="published">Diterbitkan</option>
                        <option value="archived">Diarsipkan</option>
                    </select>
                    @error('status') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ann-content" class="form-label">Isi Pengumuman</label>
                    <textarea id="ann-content" wire:model.defer="content" rows="8" class="form-textarea @error('content') is-error @enderror" placeholder="Teks pengumuman resmi..."></textarea>
                    @error('content') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Pengumuman</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari pengumuman...">
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Buat Pengumuman</button>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Judul</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal Publikasi</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($announcements as $a)
                                <tr>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $a->title }}</div>
                                    </td>
                                    <td><span class="badge badge-{{ $a->status }}">{{ $a->status }}</span></td>
                                    <td class="text-xs text-slate-500">{{ $a->published_at ? $a->published_at->format('d M Y') : '-' }}</td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $a->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Hapus pengumuman ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $a->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-12 text-center text-slate-400">Belum ada pengumuman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $announcements->links() }}</div>
        </div>
    @endif
</div>
