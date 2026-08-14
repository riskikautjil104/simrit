<div>
    @if($isEditing || $isCreating)
        {{-- Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">{{ $isCreating ? 'Tulis Berita Baru' : 'Edit Berita' }}</h2>
                <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm">Batal</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    {{-- Title, excerpt, content --}}
                    <div class="md:col-span-8 space-y-4">
                        <div>
                            <label for="news-title" class="form-label">Judul Berita</label>
                            <input type="text" id="news-title" wire:model.defer="title" class="form-input @error('title') is-error @enderror" placeholder="Ketik judul berita menarik...">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="news-category" class="form-label">Kategori Berita</label>
                            <select id="news-category" wire:model.defer="category_id" class="form-select @error('category_id') is-error @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="news-excerpt" class="form-label">Ringkasan Singkat (Excerpt)</label>
                            <textarea id="news-excerpt" wire:model.defer="excerpt" rows="2" class="form-textarea @error('excerpt') is-error @enderror" placeholder="Tulis kutipan pembuka atau penjelasan singkat..."></textarea>
                            @error('excerpt') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="news-content-source" class="form-label">Isi Lengkap Berita</label>
                            <div
                                data-tiptap
                                data-placeholder="Tulis isi berita di sini..."
                                wire:ignore
                                wire:key="news-content-{{ $isCreating ? 'create' : ($selectedNewsId ?? 'edit') }}"
                                class="tiptap-editor @error('content') is-error @enderror"
                            >
                                <div class="tiptap-toolbar" role="toolbar" aria-label="Alat pemformatan teks"></div>
                                <div class="tiptap-content"></div>
                                <textarea id="news-content-source" class="tiptap-source" wire:model.live="content" hidden></textarea>
                            </div>
                            <p class="form-hint">Isi berita wajib diisi. Gunakan toolbar di atas untuk memformat teks, atau shortcut Ctrl/Cmd+B (tebal), Ctrl/Cmd+I (miring), Ctrl/Cmd+U (garis bawah), Ctrl/Cmd+K (tautan).</p>
                            @error('content') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Image & Status --}}
                    <div class="md:col-span-4 space-y-5">
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="news-status" class="form-label">Status Penerbitan</label>
                            <select id="news-status" wire:model.defer="status" class="form-select @error('status') is-error @enderror">
                                <option value="draft">Draft (Sembunyikan)</option>
                                <option value="published">Diterbitkan (Publik)</option>
                                <option value="archived">Diarsipkan</option>
                            </select>
                            @error('status') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <label for="cover-image" class="form-label">Sampul Berita (Cover Image)</label>
                            @if($cover_image)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="object-cover w-full h-full">
                                </div>
                            @elseif($existingCoverImage)
                                <div class="mb-3 rounded-lg overflow-hidden aspect-video max-h-40 bg-slate-200">
                                    <img src="{{ asset('storage/'.$existingCoverImage) }}" alt="Cover" class="object-cover w-full h-full">
                                </div>
                            @endif

                            <input type="file" id="cover-image" wire:model="cover_image" class="form-input text-xs">
                            <p class="form-hint text-[10px]">Format: JPG, PNG, WEBP. Maksimal 5MB.</p>
                            @error('cover_image') <p class="form-error text-[10px]">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Berita</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- List --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Search & Filters --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <label for="search-news" class="sr-only">Cari judul...</label>
                        <input type="text" id="search-news" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari judul berita...">
                    </div>

                    <label for="filter-category" class="sr-only">Filter Kategori</label>
                    <select id="filter-category" wire:model.live="categoryFilter" class="form-select py-1.5 text-sm w-full sm:w-44">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" wire:click="create" class="btn btn-primary btn-sm w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg> Tulis Berita
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="table-wrap">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th scope="col">Judul Berita</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal Publikasi</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($news as $n)
                                <tr>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm truncate max-w-xs">{{ $n->title }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">Penulis: {{ $n->creator ? $n->creator->name : 'Sistem' }}</div>
                                    </td>
                                    <td>
                                        @if($n->category)
                                            <span class="text-xs text-slate-600 bg-slate-50 border border-slate-100 px-2 py-0.5 rounded">
                                                {{ $n->category->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $n->status }}">{{ $n->status }}</span>
                                    </td>
                                    <td class="text-xs text-slate-500">
                                        {{ $n->published_at ? $n->published_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" wire:click="edit({{ $n->id }})" class="btn btn-sm btn-ghost">Edit</button>
                                            <button type="button" onclick="confirm('Apakah Anda yakin ingin menghapus berita ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $n->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 empty-state">
                                        <svg class="w-10 h-10 mx-auto opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        <h3 class="mt-2">Belum ada berita</h3>
                                        <p>Silakan klik tombol "Tulis Berita" di atas untuk menambahkan berita baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $news->links() }}
            </div>
        </div>
    @endif
</div>
