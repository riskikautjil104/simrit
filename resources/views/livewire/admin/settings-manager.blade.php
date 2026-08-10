<div>
    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 mb-6">Pengaturan Situs SIMRIT</h2>

            <form wire:submit.prevent="save" class="space-y-8">
                {{-- Site Identity --}}
                <div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-5 h-px bg-slate-200 flex-shrink-0"></span> Identitas Situs
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="s-name" class="form-label">Nama Situs</label>
                            <input type="text" id="s-name" wire:model.defer="site_name" class="form-input @error('site_name') is-error @enderror">
                            @error('site_name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="s-tagline" class="form-label">Tagline / Slogan</label>
                            <input type="text" id="s-tagline" wire:model.defer="site_tagline" class="form-input" placeholder="Sistem Informasi Manajemen Ruang IT">
                        </div>
                        <div>
                            <label for="s-desc" class="form-label">Deskripsi Situs (SEO Meta Description)</label>
                            <textarea id="s-desc" wire:model.defer="site_description" rows="2" class="form-textarea" placeholder="Deskripsi singkat untuk mesin pencari..."></textarea>
                            @error('site_description') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-5 h-px bg-slate-200 flex-shrink-0"></span> Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="s-phone" class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" id="s-phone" wire:model.defer="phone" class="form-input @error('phone') is-error @enderror" placeholder="(0921) 123-456">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="s-email" class="form-label">Alamat Email</label>
                            <input type="email" id="s-email" wire:model.defer="email" class="form-input @error('email') is-error @enderror" placeholder="it@rsud-cb.go.id">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="s-addr" class="form-label">Alamat Lengkap</label>
                            <textarea id="s-addr" wire:model.defer="address" rows="2" class="form-textarea" placeholder="Jl. RS. Dr. H. Chasan Boesoirie No.1, Ternate..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-5 h-px bg-slate-200 flex-shrink-0"></span> Footer
                    </h3>
                    <div>
                        <label for="s-footer" class="form-label">Teks Footer</label>
                        <input type="text" id="s-footer" wire:model.defer="footer_text" class="form-input" placeholder="© 2025 Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate">
                    </div>
                </div>

                {{-- Logo & Favicon --}}
                <div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-5 h-px bg-slate-200 flex-shrink-0"></span> Logo & Favicon
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <label for="s-logo" class="form-label">Logo Situs</label>
                            @if($logo)
                                <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo" class="mb-2 h-12 object-contain rounded bg-white p-1 border border-slate-100">
                            @elseif($existingLogo)
                                <img src="{{ asset('storage/'.$existingLogo) }}" alt="Logo" class="mb-2 h-12 object-contain rounded bg-white p-1 border border-slate-100">
                            @endif
                            <input type="file" id="s-logo" wire:model="logo" accept="image/*" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Disarankan format PNG transparan, maks 2MB.</p>
                            @error('logo') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <label for="s-favicon" class="form-label">Favicon</label>
                            @if($favicon)
                                <img src="{{ $favicon->temporaryUrl() }}" alt="Preview Favicon" class="mb-2 h-8 w-8 object-contain rounded bg-white p-0.5 border border-slate-100">
                            @elseif($existingFavicon)
                                <img src="{{ asset('storage/'.$existingFavicon) }}" alt="Favicon" class="mb-2 h-8 w-8 object-contain rounded bg-white p-0.5 border border-slate-100">
                            @endif
                            <input type="file" id="s-favicon" wire:model="favicon" accept="image/*" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Disarankan 32×32px ICO/PNG, maks 512KB.</p>
                            @error('favicon') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Semua Pengaturan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
