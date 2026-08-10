<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AnnouncementManager extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedId;
    public $title;
    public $content;
    public $status = 'draft';
    public $published_at;

    public $isEditing = false;
    public $isCreating = false;

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $a = Announcement::findOrFail($id);
        $this->selectedId   = $a->id;
        $this->title        = $a->title;
        $this->content      = $a->content;
        $this->status       = $a->status;
        $this->published_at = $a->published_at ? $a->published_at->format('Y-m-d') : null;
        $this->isEditing    = true;
    }

    public function save()
    {
        $this->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'status'       => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $slug = \Illuminate\Support\Str::slug($this->title);

        $data = [
            'title'      => $this->title,
            'slug'       => $slug,
            'content'    => $this->content,
            'status'     => $this->status,
            'updated_by' => auth()->id(),
        ];

        if ($this->published_at) {
            $data['published_at'] = $this->published_at;
        } elseif ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->isCreating) {
            $data['created_by'] = auth()->id();
            $ann = Announcement::create($data);
            ActivityLogger::log('create', "Membuat pengumuman: {$ann->title}", $ann);
            session()->flash('success', "Pengumuman \"{$ann->title}\" berhasil dibuat.");
        } else {
            $ann = Announcement::findOrFail($this->selectedId);
            $ann->update($data);
            ActivityLogger::log('update', "Memperbarui pengumuman: {$ann->title}", $ann);
            session()->flash('success', "Pengumuman \"{$ann->title}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $ann = Announcement::findOrFail($id);
        ActivityLogger::log('delete', "Menghapus pengumuman: {$ann->title}", $ann);
        $ann->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->title = $this->content = $this->published_at = '';
        $this->status = 'draft';
    }

    public function render()
    {
        $query = Announcement::latest();
        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }
        $announcements = $query->paginate(10);

        return view('livewire.admin.announcement-manager', compact('announcements'))
            ->layout('layouts.admin', ['title' => 'Kelola Pengumuman']);
    }
}
