<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Portal' : 'Edit Portal' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="portal-name" class="form-label">Nama Portal</label>
                            <input type="text" id="portal-name" wire:model.defer="name" class="form-input @error('name') is-error @enderror" placeholder="SIMRS, Portal Pegawai, Helpdesk...">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="portal-description" class="form-label">Deskripsi Singkat</label>
                            <textarea id="portal-description" wire:model.defer="description" rows="3" class="form-textarea @error('description') is-error @enderror" placeholder="Keterangan singkat portal ini..."></textarea>
                            @error('description') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="portal-link" class="form-label">Link Portal</label>
                            <input type="url" id="portal-link" wire:model.defer="link" class="form-input @error('link') is-error @enderror" placeholder="https://simrs.example.go.id">
                            @error('link') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 space-y-4">
                            <div>
                                <label for="portal-status" class="form-label">Status</label>
                                <select id="portal-status" wire:model.defer="status" class="form-select">
                                    <option value="published">Diterbitkan</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div>
                                <label for="portal-order" class="form-label">Urutan Tampil</label>
                                <input type="number" id="portal-order" wire:model.defer="order" class="form-input" min="0">
                            </div>
                            <div>
                                <label for="portal-icon" class="form-label">Ikon Teks</label>
                                <input type="text" id="portal-icon" wire:model.defer="icon" class="form-input" placeholder="SIMRS">
                                <p class="form-hint text-[10px]">Isi singkatan atau emoji pendek, misalnya SIMRS, HD, atau 🔗.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Portal</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" wire:click="create" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                    Tambah Portal
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($portals as $portal)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1d4ed8] flex items-center justify-center text-sm font-black flex-shrink-0">
                            {{ $portal->icon ?: strtoupper(substr($portal->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm truncate">{{ $portal->name }}</span>
                                <span class="badge badge-{{ $portal->status }} flex-shrink-0">{{ $portal->status }}</span>
                            </div>
                            <p class="text-slate-500 text-xs mt-1 line-clamp-2">{{ $portal->description }}</p>
                            <a href="{{ $portal->link }}" target="_blank" rel="noopener" class="block text-[11px] text-[#1d4ed8] truncate mt-1">{{ $portal->link }}</a>
                            <div class="flex gap-1.5 mt-3">
                                <button type="button" wire:click="edit({{ $portal->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                <button type="button" onclick="confirm('Hapus portal ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $portal->id }})" class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 empty-state bg-white rounded-2xl border border-slate-100">
                        <svg class="w-10 h-10 mx-auto opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.172-1.172m11.314 0l1.172-1.172a4 4 0 00-5.656-5.656l-3 3"/></svg>
                        <h3 class="mt-2">Belum ada portal</h3>
                        <p>Tambahkan portal seperti SIMRS, Helpdesk, atau aplikasi internal lainnya.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
