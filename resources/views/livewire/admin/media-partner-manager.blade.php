<div>
    @if($isEditing || $isCreating)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tambah Media Partner' : 'Edit Media Partner' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="partner-name" class="form-label">Nama Media Partner</label>
                            <input type="text" id="partner-name" wire:model.defer="name" class="form-input @error('name') is-error @enderror" placeholder="Nama media partner...">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="partner-desc" class="form-label">Deskripsi Singkat (Opsional)</label>
                            <textarea id="partner-desc" wire:model.defer="description" rows="4" class="form-textarea @error('description') is-error @enderror" placeholder="Penjelasan singkat mengenai media partner ini..."></textarea>
                            @error('description') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="partner-link" class="form-label">Tautan Website (Opsional)</label>
                            <input type="url" id="partner-link" wire:model.defer="link" class="form-input @error('link') is-error @enderror" placeholder="https://contoh.com">
                            @error('link') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-4">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 space-y-4">
                            <div>
                                <label for="partner-status" class="form-label">Status</label>
                                <select id="partner-status" wire:model.defer="status" class="form-select">
                                    <option value="published">Diterbitkan (Tampil)</option>
                                    <option value="draft">Draft (Sembunyikan)</option>
                                </select>
                            </div>
                            <div>
                                <label for="partner-order" class="form-label">Urutan Tampil</label>
                                <input type="number" id="partner-order" wire:model.defer="order" class="form-input" min="0">
                            </div>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <label for="partner-logo" class="form-label">Logo</label>
                            @if($logo)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-32 bg-white border border-slate-100 flex items-center justify-center">
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="object-contain w-full h-full p-2">
                                </div>
                            @elseif($existingLogo)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-32 bg-white border border-slate-100 flex items-center justify-center">
                                    <img src="{{ asset('storage/'.$existingLogo) }}" alt="Logo" class="object-contain w-full h-full p-2">
                                </div>
                            @endif
                            <input type="file" id="partner-logo" wire:model="logo" accept="image/*" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
                            @error('logo') <p class="form-error text-[10px]">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Media Partner</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex justify-end">
                <button type="button" wire:click="create" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tambah Media Partner
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($partners as $p)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex gap-4">
                        @if($p->logo)
                            <div class="w-14 h-14 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->name }}" class="object-contain w-full h-full p-1">
                            </div>
                        @else
                            <div class="w-14 h-14 rounded-lg bg-blue-50 border-2 border-blue-100 flex items-center justify-center text-blue-600 font-black text-xl flex-shrink-0">
                                {{ strtoupper(substr($p->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 text-sm truncate">{{ $p->name }}</div>
                            @if($p->link)
                                <div class="text-[11px] text-[#1d4ed8] truncate">{{ $p->link }}</div>
                            @endif
                            <span class="badge badge-{{ $p->status }} mt-1">{{ $p->status }}</span>
                            <div class="flex gap-1.5 mt-3">
                                <button type="button" wire:click="showDetails({{ $p->id }})" class="btn btn-sm btn-secondary">Detail</button>
                                <button type="button" wire:click="edit({{ $p->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                <button type="button" onclick="confirm('Hapus media partner ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $p->id }})" class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 empty-state">
                        <svg class="w-10 h-10 mx-auto opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <h3 class="mt-2">Belum ada media partner</h3>
                        <p>Silakan klik tombol "Tambah Media Partner" di atas untuk menambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if($viewingPartner)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="closeDetails">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-slate-800">Detail Media Partner</h2>
                    <button type="button" wire:click="closeDetails" class="btn btn-sm btn-ghost">Tutup</button>
                </div>
                <div class="p-6 space-y-5">
                    <div class="flex items-start gap-4">
                        @if($viewingPartner->logo)
                            <div class="w-20 h-20 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/'.$viewingPartner->logo) }}" alt="{{ $viewingPartner->name }}" class="object-contain w-full h-full p-2">
                            </div>
                        @else
                            <div class="w-20 h-20 rounded-xl bg-blue-50 border-2 border-blue-100 flex items-center justify-center text-blue-600 font-black text-2xl flex-shrink-0">
                                {{ strtoupper(substr($viewingPartner->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-slate-900 break-words">{{ $viewingPartner->name }}</h3>
                            <span class="badge badge-{{ $viewingPartner->status }} mt-2">{{ $viewingPartner->status }}</span>
                            <p class="mt-2 text-xs text-slate-500">Urutan tampil: {{ $viewingPartner->sort_order ?? 0 }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi</div>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $viewingPartner->description ?: 'Belum ada deskripsi.' }}</p>
                    </div>

                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tautan</div>
                        @if($viewingPartner->link)
                            <a href="{{ $viewingPartner->link }}" target="_blank" rel="noopener" class="text-sm font-semibold text-[#1d4ed8] break-all hover:underline">
                                {{ $viewingPartner->link }}
                            </a>
                        @else
                            <p class="text-sm text-slate-500">Belum ada tautan.</p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-100 px-6 py-4">
                    <button type="button" wire:click="edit({{ $viewingPartner->id }})" class="btn btn-secondary btn-sm">Edit</button>
                    <button type="button" wire:click="closeDetails" class="btn btn-primary btn-sm">Selesai</button>
                </div>
            </div>
        </div>
    @endif
</div>
