<?php

namespace App\Livewire\Admin;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryManager extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';

    // Gallery album fields
    public $selectedGalleryId;
    public $title;
    public $description;
    public $status = 'published';
    public $cover_image;
    public $existingCoverImage;

    // Photo upload fields
    public $photos      = [];
    public $captions    = [];

    public $isEditing   = false;
    public $isCreating  = false;
    public $isViewItems = false; // viewing items inside an album

    public $currentGallery; // for item view

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        $this->selectedGalleryId  = $gallery->id;
        $this->title              = $gallery->title;
        $this->description        = $gallery->description;
        $this->status             = $gallery->status;
        $this->existingCoverImage = $gallery->cover_image;
        $this->cover_image        = null;
        $this->isEditing          = true;
    }

    public function save()
    {
        $this->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $slug = Str::slug($this->title);

        if ($this->isCreating) {
            if (Gallery::where('slug', $slug)->count()) {
                $slug .= '-' . time();
            }
            $data = [
                'title'       => $this->title,
                'slug'        => $slug,
                'description' => $this->description,
                'status'      => $this->status,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ];
            if ($this->status === 'published') {
                $data['published_at'] = now();
            }
            if ($this->cover_image) {
                $filename = 'galleries/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }
            $gallery = Gallery::create($data);
            ActivityLogger::log('create', "Membuat album galeri: {$gallery->title}", $gallery);
            session()->flash('success', "Album \"{$gallery->title}\" berhasil dibuat.");
        } else {
            $gallery = Gallery::findOrFail($this->selectedGalleryId);
            if (Gallery::where('slug', $slug)->where('id', '!=', $gallery->id)->count()) {
                $slug .= '-' . time();
            }
            $data = [
                'title'       => $this->title,
                'slug'        => $slug,
                'description' => $this->description,
                'status'      => $this->status,
                'updated_by'  => auth()->id(),
            ];
            if ($this->cover_image) {
                if ($gallery->cover_image) {
                    Storage::disk('public')->delete($gallery->cover_image);
                }
                $filename = 'galleries/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }
            $gallery->update($data);
            ActivityLogger::log('update', "Memperbarui album: {$gallery->title}", $gallery);
            session()->flash('success', "Album \"{$gallery->title}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function viewItems($id)
    {
        $this->currentGallery = Gallery::with('items')->findOrFail($id);
        $this->isViewItems    = true;
        $this->photos         = [];
        $this->captions       = [];
    }

    public function uploadPhotos()
    {
        $this->validate([
            'photos'   => 'required|array|min:1',
            'photos.*' => 'image|max:5120',
        ]);

        $gallery = $this->currentGallery;
        $count   = 0;

        foreach ($this->photos as $idx => $photo) {
            $filename = 'galleries/items/' . Str::random(20) . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('', $filename, 'public');

            GalleryItem::create([
                'gallery_id'        => $gallery->id,
                'file_path'         => $filename,
                'original_filename' => $photo->getClientOriginalName(),
                'caption'           => $this->captions[$idx] ?? null,
                'sort_order'        => $gallery->items()->count() + $count + 1,
            ]);
            $count++;
        }

        $this->photos   = [];
        $this->captions = [];
        // Refresh items
        $this->currentGallery = Gallery::with('items')->findOrFail($gallery->id);

        ActivityLogger::log('create', "Mengunggah {$count} foto ke album: {$gallery->title}");
        session()->flash('success', "{$count} foto berhasil diunggah ke album.");
    }

    public function deleteItem($itemId)
    {
        $item = GalleryItem::findOrFail($itemId);
        Storage::disk('public')->delete($item->file_path);
        $item->delete();

        $this->currentGallery = Gallery::with('items')->findOrFail($this->currentGallery->id);
        session()->flash('success', 'Foto berhasil dihapus dari album.');
    }

    public function delete($id)
    {
        $gallery = Gallery::with('items')->findOrFail($id);
        foreach ($gallery->items as $item) {
            Storage::disk('public')->delete($item->file_path);
            $item->delete();
        }
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }
        ActivityLogger::log('delete', "Menghapus album: {$gallery->title}", $gallery);
        $gallery->delete();
        session()->flash('success', 'Album galeri berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = $this->isViewItems = false;
        $this->selectedGalleryId = null;
        $this->title = $this->description = '';
        $this->status = 'published';
        $this->cover_image = null;
        $this->existingCoverImage = null;
        $this->currentGallery = null;
        $this->photos = [];
        $this->captions = [];
    }

    public function render()
    {
        $query = Gallery::withCount('items')->latest();
        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }
        $galleries = $query->paginate(10);

        return view('livewire.admin.gallery-manager', compact('galleries'))
            ->layout('layouts.admin', ['title' => 'Kelola Galeri Foto']);
    }
}
