<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Unggah Dokumen Baru' : 'Edit Dokumen' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="doc-title" class="form-label">Judul Dokumen</label>
                        <input type="text" id="doc-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Nama/judul dokumen...">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="doc-cat" class="form-label">Kategori Dokumen</label>
                        <select id="doc-cat" wire:model.defer="document_category_id" class="form-select @error('document_category_id') is-error @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('document_category_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="doc-desc" class="form-label">Keterangan (Opsional)</label>
                    <textarea id="doc-desc" wire:model.defer="description" rows="3" class="form-textarea" placeholder="Deskripsi singkat dokumen..."></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="doc-status" class="form-label">Status</label>
                        <select id="doc-status" wire:model.defer="status" class="form-select @error('status') is-error @enderror">
                            <option value="published">Diterbitkan (Publik)</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Diarsipkan</option>
                        </select>
                        @error('status') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="doc-file" class="form-label">{{ $isCreating ? 'Berkas Dokumen (Wajib)' : 'Ganti Berkas (Opsional)' }}</label>
                        @if($existingFilename && !$isCreating)
                            <p class="text-xs text-slate-500 mb-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 truncate">
                                {{ $existingFilename }}
                            </p>
                        @endif
                        <input type="file" id="doc-file" wire:model="file" class="form-input text-xs">
                        <p class="form-hint text-[10px]">PDF, Word, Excel, PowerPoint, ZIP. Maks 20MB.</p>
                        @error('file') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $isCreating ? 'Unggah Dokumen' : 'Simpan Perubahan' }}</span>
                        <span wire:loading>Mengunggah...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex gap-3 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari judul dokumen...">
                    </div>
                    <select wire:model.live="categoryFilter" class="form-select py-1.5 text-sm w-40">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Unggah Dokumen</button>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Judul Dokumen</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Berkas</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $doc->title }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $doc->description }}</div>
                                    </td>
                                    <td class="text-xs text-slate-600">{{ $doc->category ? $doc->category->name : '-' }}</td>
                                    <td class="text-xs text-slate-500 truncate max-w-[120px]">{{ $doc->original_filename }}</td>
                                    <td><span class="badge badge-{{ $doc->status }}">{{ $doc->status }}</span></td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $doc->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Hapus dokumen ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $doc->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-12 text-center text-slate-400">Belum ada dokumen diunggah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $documents->links() }}</div>
        </div>
    @endif
</div>
