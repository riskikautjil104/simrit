<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MediaManager extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $files = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function upload()
    {
        $this->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,webp,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,webm,ogg,zip',
        ]);

        $count = 0;
        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            $ext          = $file->getClientOriginalExtension();
            $mimeType     = $file->getMimeType();
            $size         = $file->getSize();
            $type         = $this->detectType($mimeType);
            $folder       = 'media/' . $type;
            $path         = $folder . '/' . Str::random(20) . '.' . $ext;

            $file->storeAs('', $path, 'public');

            Media::create([
                'disk'              => 'public',
                'path'              => $path,
                'original_filename' => $originalName,
                'mime_type'         => $mimeType,
                'file_size'         => $size,
                'uploaded_by'       => auth()->id(),
            ]);
            $count++;
        }

        $this->files = [];
        ActivityLogger::log('create', "Mengunggah {$count} berkas ke pustaka media.");
        session()->flash('success', "{$count} berkas berhasil diunggah ke pustaka media.");
    }

    protected function detectType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if ($mimeType === 'application/pdf') return 'document';
        return 'other';
    }

    public function delete($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();
        ActivityLogger::log('delete', "Menghapus berkas media: {$media->original_filename}");
        session()->flash('success', 'Berkas media berhasil dihapus.');
    }

    public function render()
    {
        $query = Media::latest();
        if ($this->search) {
            $query->where('original_filename', 'like', '%' . $this->search . '%');
        }
        if ($this->typeFilter) {
            $mimePatterns = [
                'image' => 'image/%',
                'video' => 'video/%',
                'document' => 'application/%',
                'other' => null,
            ];

            if ($this->typeFilter === 'other') {
                $query->where(function ($query) {
                    $query->where('mime_type', 'not like', 'image/%')
                        ->where('mime_type', 'not like', 'video/%')
                        ->where('mime_type', 'not like', 'application/%');
                });
            } elseif (isset($mimePatterns[$this->typeFilter])) {
                $query->where('mime_type', 'like', $mimePatterns[$this->typeFilter]);
            }
        }
        $mediaItems = $query->paginate(24);

        return view('livewire.admin.media-manager', compact('mediaItems'))
            ->layout('layouts.admin', ['title' => 'Pustaka Media']);
    }
}
