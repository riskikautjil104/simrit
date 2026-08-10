<?php

namespace App\Livewire\Admin;

use App\Models\Video;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class VideoManager extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedId;
    public $title;
    public $description;
    public $embed_url;
    public $status = 'published';

    public $isEditing  = false;
    public $isCreating = false;

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $v = Video::findOrFail($id);
        $this->selectedId   = $v->id;
        $this->title        = $v->title;
        $this->description  = $v->description;
        $this->embed_url    = $v->embed_url;
        $this->status       = $v->status;
        $this->isEditing    = true;
    }

    public function save()
    {
        $this->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_url'   => 'required|url',
            'status'      => 'required|in:draft,published,archived',
        ]);

        $data = [
            'title'       => $this->title,
            'description' => $this->description,
            'embed_url'   => $this->embed_url,
            'status'      => $this->status,
            'updated_by'  => auth()->id(),
        ];

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->isCreating) {
            $data['created_by'] = auth()->id();
            $v = Video::create($data);
            ActivityLogger::log('create', "Menambahkan video: {$v->title}", $v);
            session()->flash('success', "Video \"{$v->title}\" berhasil ditambahkan.");
        } else {
            $v = Video::findOrFail($this->selectedId);
            $v->update($data);
            ActivityLogger::log('update', "Memperbarui video: {$v->title}", $v);
            session()->flash('success', "Video \"{$v->title}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $v = Video::findOrFail($id);
        ActivityLogger::log('delete', "Menghapus video: {$v->title}", $v);
        $v->delete();
        session()->flash('success', 'Video berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->title = $this->description = $this->embed_url = '';
        $this->status = 'published';
    }

    public function render()
    {
        $query = Video::latest();
        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }
        $videos = $query->paginate(10);

        return view('livewire.admin.video-manager', compact('videos'))
            ->layout('layouts.admin', ['title' => 'Kelola Video']);
    }
}
