<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Anggota Tim IT' : 'Edit Anggota Tim' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <div class="md:col-span-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="team-name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="team-name" wire:model.defer="name" class="form-input @error('name') is-error @enderror" placeholder="Nama lengkap anggota...">
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="team-pos" class="form-label">Jabatan / Posisi</label>
                                <input type="text" id="team-pos" wire:model.defer="position" class="form-input @error('position') is-error @enderror" placeholder="e.g. Kepala Ruang IT, Staf Programmer...">
                                @error('position') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="team-dept" class="form-label">Bagian / Departemen (Opsional)</label>
                            <input type="text" id="team-dept" wire:model.defer="department" class="form-input" placeholder="e.g. Ruang IT, SIMRS, Jaringan...">
                        </div>
                        <div>
                            <label for="team-bio" class="form-label">Biografi Singkat (Opsional)</label>
                            <textarea id="team-bio" wire:model.defer="biography" rows="4" class="form-textarea" placeholder="Latar belakang, pendidikan, keahlian..."></textarea>
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 space-y-4">
                            <div>
                                <label for="team-status" class="form-label">Status</label>
                                <select id="team-status" wire:model.defer="status" class="form-select">
                                    <option value="published">Diterbitkan (Tampil)</option>
                                    <option value="draft">Draft (Sembunyikan)</option>
                                </select>
                            </div>
                            <div>
                                <label for="team-order" class="form-label">Urutan Tampil</label>
                                <input type="number" id="team-order" wire:model.defer="order" class="form-input" min="0">
                            </div>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <label for="team-photo" class="form-label">Foto Profil (Opsional)</label>
                            @if($photo)
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="mb-2 rounded-full w-20 h-20 object-cover mx-auto border-2 border-blue-100">
                            @elseif($existingPhoto)
                                <img src="{{ asset('storage/'.$existingPhoto) }}" alt="Foto Profil" class="mb-2 rounded-full w-20 h-20 object-cover mx-auto border-2 border-blue-100">
                            @endif
                            <input type="file" id="team-photo" wire:model="photo" accept="image/*" class="form-input text-xs">
                            @error('photo') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Anggota Tim</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" wire:click="create" class="btn btn-primary btn-sm"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tambah Anggota Tim</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($members as $m)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex gap-4">
                        @if($m->photo)
                            <img src="{{ asset('storage/'.$m->photo) }}" alt="{{ $m->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-slate-100 flex-shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-full bg-blue-50 border-2 border-blue-100 flex items-center justify-center text-blue-600 font-black text-xl flex-shrink-0">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 text-sm truncate">{{ $m->name }}</div>
                            <div class="text-[11px] text-[#1d4ed8] font-bold uppercase tracking-wider truncate">{{ $m->position }}</div>
                            <div class="text-[10px] text-slate-400 truncate">{{ $m->department ?: 'Ruang IT' }}</div>
                            <span class="badge badge-{{ $m->status }} mt-1">{{ $m->status }}</span>
                            <div class="flex gap-1.5 mt-3">
                                <button type="button" wire:click="edit({{ $m->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                <button type="button" onclick="confirm('Hapus anggota ini dari daftar tim?') || event.stopImmediatePropagation()" wire:click="delete({{ $m->id }})" class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada anggota tim IT yang didaftarkan.</div>
                @endforelse
            </div>
        </div>
    @endif
</div>
