<?php

namespace App\Livewire\Admin;

use App\Models\News;
use App\Models\Category;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsManager extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $categoryFilter = '';

    // Form fields
    public $selectedNewsId;
    public $category_id;
    public $title;
    public $excerpt;
    public $content;
    public $status = 'draft';
    public $cover_image;
    public $existingCoverImage;

    public $isEditing = false;
    public $isCreating = false;

    protected $paginationTheme = 'bootstrap'; // Livewire handles tailwind in newer versions but custom pagination works too

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $this->selectedNewsId = $news->id;
        $this->category_id = $news->category_id;
        $this->title = $news->title;
        $this->excerpt = $news->excerpt;
        $this->content = $news->content;
        $this->status = $news->status;
        $this->existingCoverImage = $news->cover_image;
        $this->cover_image = null;

        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string',
            'content'     => 'required|string|min:1',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $slug = Str::slug($this->title);

        if ($this->isCreating) {
            // Check unique slug
            $count = News::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }

            $data = [
                'category_id' => $this->category_id ?: null,
                'title'       => $this->title,
                'slug'        => $slug,
                'excerpt'     => $this->excerpt,
                'content'     => $this->content,
                'status'      => $this->status,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ];

            if ($this->cover_image) {
                $filename = 'news/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }

            if ($this->status === 'published') {
                $data['published_at'] = now();
            }

            $news = News::create($data);

            ActivityLogger::log('create', "Membuat berita baru: {$news->title}", $news);
            session()->flash('success', "Berita {$news->title} berhasil dibuat.");
        } else {
            $news = News::findOrFail($this->selectedNewsId);

            // Check unique slug
            $count = News::where('slug', $slug)->where('id', '!=', $news->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }

            $data = [
                'category_id' => $this->category_id ?: null,
                'title'       => $this->title,
                'slug'        => $slug,
                'excerpt'     => $this->excerpt,
                'content'     => $this->content,
                'status'      => $this->status,
                'updated_by'  => auth()->id(),
            ];

            if ($this->cover_image) {
                if ($news->cover_image) {
                    Storage::disk('public')->delete($news->cover_image);
                }
                $filename = 'news/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }

            if ($this->status === 'published' && !$news->published_at) {
                $data['published_at'] = now();
            }

            $news->update($data);

            ActivityLogger::log('update', "Memperbarui berita: {$news->title}", $news);
            session()->flash('success', "Berita {$news->title} berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        ActivityLogger::log('delete', "Menghapus berita: {$news->title}", $news);
        session()->flash('success', "Berita {$news->title} berhasil dihapus.");
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->isCreating = false;
        $this->selectedNewsId = null;
        $this->category_id = '';
        $this->title = '';
        $this->excerpt = '';
        $this->content = '';
        $this->status = 'draft';
        $this->cover_image = null;
        $this->existingCoverImage = null;
    }

    public function render()
    {
        $query = News::with('category')->latest();

        if ($this->search) {
            $query->where(fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('excerpt', 'like', '%' . $this->search . '%'));
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        $news = $query->paginate(10);
        $categories = Category::all();

        return view('livewire.admin.news-manager', compact('news', 'categories'))
            ->layout('layouts.admin', ['title' => 'Kelola Berita']);
    }
}
