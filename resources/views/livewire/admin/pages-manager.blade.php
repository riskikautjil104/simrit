<div>
    @if($isEditing)
        {{-- Edit Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">Edit Halaman: {{ $title }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    {{-- Title & Excerpt (Col-span 8) --}}
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="title" class="form-label">Judul Halaman</label>
                            <input type="text" id="title" wire:model.defer="title" class="form-input @error('title') is-error @enderror">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="excerpt" class="form-label">Kutipan Singkat (Excerpt)</label>
                            <textarea id="excerpt" wire:model.defer="excerpt" rows="2" class="form-textarea @error('excerpt') is-error @enderror" placeholder="Penjelasan singkat halaman..."></textarea>
                            @error('excerpt') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="content" class="form-label">Konten Halaman (HTML didukung)</label>
                            <textarea id="content" wire:model.defer="content" rows="12" class="form-textarea @error('content') is-error @enderror font-mono text-sm" placeholder="Isi konten halaman profil..."></textarea>
                            @error('content') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Image & Status (Col-span 4) --}}
                    <div class="md:col-span-4 space-y-5">
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="status-select" class="form-label">Status Tayang</label>
                            <select id="status-select" wire:model.defer="status" class="form-select @error('status') is-error @enderror">
                                <option value="draft">Draft (Disembunyikan)</option>
                                <option value="published">Diterbitkan (Publik)</option>
                                <option value="archived">Diarsipkan</option>
                            </select>
                            @error('status') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="featured-image" class="form-label">Gambar Utama (Featured Image)</label>
                            @if($featured_image)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ $featured_image->temporaryUrl() }}" alt="Preview unggahan" class="object-cover w-full h-full">
                                </div>
                            @elseif($existingFeaturedImage)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ asset('storage/'.$existingFeaturedImage) }}" alt="Featured image" class="object-cover w-full h-full">
                                </div>
                            @endif

                            <input type="file" id="featured-image" wire:model="featured_image" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Format: JPG, PNG, WEBP. Maksimal 5MB.</p>
                            @error('featured_image') <p class="form-error text-[10px]">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Perubahan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- List --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr>
                            <th scope="col">Nama Halaman</th>
                            <th scope="col">Kutipan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Terakhir Diperbarui</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pages as $p)
                            <tr>
                                <td>
                                    <div class="font-bold text-slate-800 text-sm">{{ $p->title }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-mono">/profil/{{ $p->slug }}</div>
                                </td>
                                <td>
                                    <div class="text-slate-500 text-xs truncate max-w-xs sm:max-w-md">
                                        {{ $p->excerpt ?: '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $p->status }}">{{ $p->status }}</span>
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $p->updated_at->diffForHumans() }}
                                </td>
                                <td class="text-right">
                                    <button type="button" wire:click="edit({{ $p->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
