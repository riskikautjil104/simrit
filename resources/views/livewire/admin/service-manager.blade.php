<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Layanan IT Baru' : 'Edit Layanan IT' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="svc-title" class="form-label">Nama Layanan</label>
                            <input type="text" id="svc-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Nama layanan IT...">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="svc-short" class="form-label">Deskripsi Singkat (Tampil di Homepage & Katalog)</label>
                            <textarea id="svc-short" wire:model.defer="short_description" rows="2" class="form-textarea @error('short_description') is-error @enderror" placeholder="Penjelasan singkat layanan ini..."></textarea>
                            @error('short_description') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="svc-content" class="form-label">Deskripsi Lengkap (HTML didukung)</label>
                            <textarea id="svc-content" wire:model.defer="content" rows="8" class="form-textarea @error('content') is-error @enderror font-mono text-sm"></textarea>
                            @error('content') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 space-y-4">
                            <div>
                                <label for="svc-status" class="form-label">Status</label>
                                <select id="svc-status" wire:model.defer="status" class="form-select">
                                    <option value="published">Diterbitkan</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Diarsipkan</option>
                                </select>
                            </div>
                            <div>
                                <label for="svc-order" class="form-label">Urutan Tampil</label>
                                <input type="number" id="svc-order" wire:model.defer="order" class="form-input" min="0">
                            </div>
                            <div>
                                <label for="svc-icon" class="form-label">Icon (Emoji)</label>
                                <input type="text" id="svc-icon" wire:model.defer="icon" class="form-input" placeholder="Ikon teks (opsional)">
                                <p class="form-hint text-[10px]">Bisa isi emoji atau SVG singkat untuk ikon layanan.</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <label for="svc-image" class="form-label">Gambar Layanan (Opsional)</label>
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="mb-2 rounded-lg aspect-video max-h-28 object-cover w-full">
                            @elseif($existingImage)
                                <img src="{{ asset('storage/'.$existingImage) }}" alt="Layanan" class="mb-2 rounded-lg aspect-video max-h-28 object-cover w-full">
                            @endif
                            <input type="file" id="svc-image" wire:model="image" class="form-input text-xs">
                            @error('image') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Layanan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" wire:click="create" class="btn btn-primary btn-sm"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tambah Layanan IT</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($services as $svc)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                            @if($svc->icon)
                                {!! $svc->icon !!}
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 3.75h4.5A1.75 1.75 0 0116 5.5v13A1.75 1.75 0 0114.25 20h-4.5A1.75 1.75 0 018 18.5v-13a1.75 1.75 0 011.75-1.75zM11 17h2M10.5 6h3"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm truncate">{{ $svc->title }}</span>
                                <span class="badge badge-{{ $svc->status }} flex-shrink-0">{{ $svc->status }}</span>
                            </div>
                            <p class="text-slate-500 text-xs mt-1 line-clamp-2">{{ $svc->short_description }}</p>
                            <div class="flex gap-1.5 mt-3">
                                <button type="button" wire:click="edit({{ $svc->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                <button type="button" onclick="confirm('Hapus layanan ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $svc->id }})" class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada layanan IT ditambahkan.</div>
                @endforelse
            </div>
        </div>
    @endif
</div>
