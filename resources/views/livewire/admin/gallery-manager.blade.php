<div>
    @if($isViewItems && $currentGallery)
        {{-- Album Photo Manager --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Album: {{ $currentGallery->title }}</h2>
                    <p class="text-xs text-slate-500">{{ $currentGallery->items_count ?? $currentGallery->items->count() }} foto tersimpan</p>
                </div>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">← Kembali ke Daftar Album</button>
            </div>

            {{-- Upload form --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-700 mb-4">Unggah Foto ke Album Ini</h3>
                <form wire:submit.prevent="uploadPhotos" class="space-y-4">
                    <div>
                        <label for="gallery-photos" class="form-label">Pilih Foto (bisa lebih dari satu)</label>
                        <input type="file" id="gallery-photos" wire:model="photos" multiple class="form-input text-xs">
                        <p class="form-hint text-[10px]">Format: JPG, PNG, WEBP. Maksimal 5MB per foto.</p>
                        @error('photos') <p class="form-error">{{ $message }}</p> @enderror
                        @error('photos.*') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary btn-sm flex items-center gap-1.5" wire:loading.attr="disabled">
                            <span wire:loading.remove class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Unggah Foto</span>
                            <span wire:loading>Mengunggah...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Existing photos --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($currentGallery->items as $item)
                    <div class="relative group bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                        <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank">
                            <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->caption }}" class="aspect-square object-cover w-full h-full group-hover:opacity-80 transition-opacity">
                        </a>
                        <div class="absolute top-1.5 right-1.5">
                            <button type="button" onclick="confirm('Hapus foto ini dari album?') || event.stopImmediatePropagation()" wire:click="deleteItem({{ $item->id }})" class="bg-red-500 hover:bg-red-600 text-white p-1 rounded-md shadow" aria-label="Hapus foto"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        @if($item->caption)
                            <div class="p-2 text-[10px] text-slate-500 truncate">{{ $item->caption }}</div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <p>Album ini masih kosong. Unggah foto menggunakan form di atas.</p>
                    </div>
                @endforelse
            </div>
        </div>

    @elseif($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Buat Album Galeri Baru' : 'Edit Album Galeri' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label for="gal-title" class="form-label">Judul Album</label>
                    <input type="text" id="gal-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Nama album dokumentasi...">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="gal-desc" class="form-label">Deskripsi Album (Opsional)</label>
                    <textarea id="gal-desc" wire:model.defer="description" rows="3" class="form-textarea" placeholder="Keterangan singkat album..."></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="gal-status" class="form-label">Status</label>
                        <select id="gal-status" wire:model.defer="status" class="form-select">
                            <option value="published">Diterbitkan (Publik)</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Diarsipkan</option>
                        </select>
                    </div>
                    <div>
                        <label for="gal-cover" class="form-label">Foto Sampul Album (Opsional)</label>
                        @if($cover_image)
                            <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="mb-2 rounded-lg aspect-video max-h-28 object-cover bg-slate-100 w-full">
                        @elseif($existingCoverImage)
                            <img src="{{ asset('storage/'.$existingCoverImage) }}" alt="Cover" class="mb-2 rounded-lg aspect-video max-h-28 object-cover bg-slate-100 w-full">
                        @endif
                        <input type="file" id="gal-cover" wire:model="cover_image" class="form-input text-xs">
                        @error('cover_image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Album</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

    @else
        {{-- Gallery List --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari album galeri...">
                </div>
                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Buat Album</button>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Judul Album</th>
                                <th scope="col">Jumlah Foto</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($galleries as $gal)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @if($gal->cover_image)
                                                <img src="{{ asset('storage/'.$gal->cover_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-slate-100 flex-shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5zM7 16l2.5-3 2 2 2.5-3 3 4M8 8.5h.01"/></svg></div>
                                            @endif
                                            <div class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $gal->title }}</div>
                                        </div>
                                    </td>
                                    <td class="text-xs text-slate-600">{{ $gal->items_count }} foto</td>
                                    <td><span class="badge badge-{{ $gal->status }}">{{ $gal->status }}</span></td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="viewItems({{ $gal->id }})" class="btn btn-sm btn-secondary">Foto</button>
                                            <button type="button" wire:click="edit({{ $gal->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Hapus seluruh album ini beserta fotonya?') || event.stopImmediatePropagation()" wire:click="delete({{ $gal->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-12 text-center text-slate-400">Belum ada album galeri dibuat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $galleries->links() }}</div>
        </div>
    @endif
</div>
