<div>
    {{-- Upload section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Unggah Berkas ke Pustaka Media</h3>
        <form wire:submit.prevent="upload" class="space-y-3">
            <div>
                <label for="media-files" class="form-label">Pilih Berkas (bisa lebih dari satu)</label>
                <input type="file" id="media-files" wire:model="files" multiple class="form-input text-xs">
                <p class="form-hint text-[10px]">Gambar (JPG/PNG/WEBP), PDF, Word, Excel, MP4, ZIP. Maks 20MB per berkas.</p>
                @error('files.*') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove>⬆️ Unggah ke Pustaka</span>
                    <span wire:loading>Mengunggah...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Filter & Grid --}}
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input pl-8 py-1.5 text-sm" placeholder="Cari nama berkas...">
            </div>
            <select wire:model.live="typeFilter" class="form-select py-1.5 text-sm w-40">
                <option value="">Semua Tipe</option>
                <option value="image">Gambar</option>
                <option value="video">Video</option>
                <option value="document">Dokumen</option>
                <option value="other">Lainnya</option>
            </select>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($mediaItems as $media)
                <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                    <div class="aspect-square bg-slate-50 relative">
                        @if($media->type === 'image')
                            <img src="{{ $media->url }}" alt="{{ $media->original_filename }}" class="object-cover w-full h-full">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                <span class="text-3xl">
                                    @if($media->type === 'video') Video
                                    @elseif($media->type === 'document') Dokumen
                                    @else Berkas @endif
                                </span>
                                <span class="text-[9px] mt-1 uppercase font-bold text-slate-500">{{ pathinfo($media->original_filename, PATHINFO_EXTENSION) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-2">
                        <p class="text-[10px] text-slate-600 truncate font-medium" title="{{ $media->original_filename }}">{{ $media->original_filename }}</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">{{ number_format($media->file_size / 1024, 1) }} KB</p>
                        <button type="button" onclick="confirm('Hapus berkas ini dari pustaka?') || event.stopImmediatePropagation()" wire:click="delete({{ $media->id }})" class="mt-1.5 text-[9px] text-red-500 hover:text-red-700 font-bold" aria-label="Hapus berkas">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 empty-state">
                    <svg class="w-12 h-12 mx-auto opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    <h3>Pustaka media kosong</h3>
                    <p>Unggah berkas menggunakan form di atas.</p>
                </div>
            @endforelse
        </div>

        <div>{{ $mediaItems->links() }}</div>
    </div>
</div>
