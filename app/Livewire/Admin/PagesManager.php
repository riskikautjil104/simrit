<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PagesManager extends Component
{
    use WithFileUploads;

    public $pages;
    public $selectedPageId;
    public $title;
    public $excerpt;
    public $content;
    public $status = 'published';
    public $featured_image;
    public $existingFeaturedImage;

    public $isEditing = false;

    public function mount()
    {
        $this->loadPages();
    }

    public function loadPages()
    {
        $this->pages = Page::all();
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $this->selectedPageId = $page->id;
        $this->title = $page->title;
        $this->excerpt = $page->excerpt;
        $this->content = $page->content;
        $this->status = $page->status;
        $this->existingFeaturedImage = $page->featured_image;
        $this->featured_image = null;

        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'title'          => 'required|string|max:255',
            'excerpt'        => 'nullable|string',
            'content'        => 'nullable|string',
            'status'         => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $page = Page::findOrFail($this->selectedPageId);
        $slug = Str::slug($this->title);

        // Check if slug is unique for pages excluding the current one
        $count = Page::where('slug', $slug)->where('id', '!=', $page->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $data = [
            'title'      => $this->title,
            'slug'       => $slug,
            'excerpt'    => $this->excerpt,
            'content'    => $this->content,
            'status'     => $this->status,
            'updated_by' => auth()->id(),
        ];

        if ($this->featured_image) {
            // Delete old file
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $filename = 'pages/' . Str::random(20) . '.' . $this->featured_image->getClientOriginalExtension();
            $this->featured_image->storeAs('', $filename, 'public');
            $data['featured_image'] = $filename;
        }

        if ($this->status === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        }

        $page->update($data);

        ActivityLogger::log('update', "Memperbarui halaman profil: {$page->title}", $page);

        session()->flash('success', "Halaman {$page->title} berhasil diperbarui.");
        $this->resetForm();
        $this->loadPages();
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->selectedPageId = null;
        $this->title = '';
        $this->excerpt = '';
        $this->content = '';
        $this->status = 'published';
        $this->featured_image = null;
        $this->existingFeaturedImage = null;
    }

    public function render()
    {
        return view('livewire.admin.pages-manager')
            ->layout('layouts.admin', ['title' => 'Kelola Halaman Profil']);
    }
}
