<?php

namespace App\Livewire\Admin;

use App\Models\TeamMember;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TeamManager extends Component
{
    use WithFileUploads;

    public $selectedId;
    public $name;
    public $position;
    public $department;
    public $biography;
    public $photo;
    public $existingPhoto;
    public $status = 'published';
    public $order  = 0;

    public $isEditing  = false;
    public $isCreating = false;

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $m = TeamMember::findOrFail($id);
        $this->selectedId    = $m->id;
        $this->name          = $m->name;
        $this->position      = $m->position;
        $this->department    = $m->department;
        $this->biography     = $m->biography;
        $this->existingPhoto = $m->photo;
        $this->photo         = null;
        $this->status        = $m->status;
        $this->order         = $m->order ?? 0;
        $this->isEditing     = true;
    }

    public function save()
    {
        $this->validate([
            'name'       => 'required|string|max:255',
            'position'   => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'biography'  => 'nullable|string',
            'photo'      => 'nullable|image|max:5120',
            'status'     => 'required|in:draft,published,archived',
            'order'      => 'nullable|integer|min:0',
        ]);

        $data = [
            'name'       => $this->name,
            'position'   => $this->position,
            'department' => $this->department,
            'biography'  => $this->biography,
            'status'     => $this->status,
            'sort_order' => $this->order ?? 0,
            'updated_by' => auth()->id(),
        ];

        if ($this->photo) {
            $filename = 'team/' . Str::random(20) . '.' . $this->photo->getClientOriginalExtension();
            $this->photo->storeAs('', $filename, 'public');
            $data['photo'] = $filename;
        }

        if ($this->isCreating) {
            $data['created_by'] = auth()->id();
            $m = TeamMember::create($data);
            ActivityLogger::log('create', "Menambahkan anggota tim: {$m->name}", $m);
            session()->flash('success', "\"{$m->name}\" berhasil ditambahkan ke tim IT.");
        } else {
            $m = TeamMember::findOrFail($this->selectedId);
            if (isset($data['photo']) && $m->photo) {
                Storage::disk('public')->delete($m->photo);
            }
            $m->update($data);
            ActivityLogger::log('update', "Memperbarui anggota tim: {$m->name}", $m);
            session()->flash('success', "Data \"{$m->name}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $m = TeamMember::findOrFail($id);
        if ($m->photo) {
            Storage::disk('public')->delete($m->photo);
        }
        ActivityLogger::log('delete', "Menghapus anggota tim: {$m->name}", $m);
        $m->delete();
        session()->flash('success', 'Anggota tim berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->name = $this->position = $this->department = $this->biography = '';
        $this->photo = null;
        $this->existingPhoto = null;
        $this->status = 'published';
        $this->order = 0;
    }

    public function render()
    {
        $members = TeamMember::orderBy('sort_order')->get();

        return view('livewire.admin.team-manager', compact('members'))
            ->layout('layouts.admin', ['title' => 'Kelola Tim IT']);
    }
}
