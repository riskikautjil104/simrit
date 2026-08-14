<?php

namespace App\Livewire\Admin;

use App\Models\MediaPartner;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MediaPartnerManager extends Component
{
    use WithFileUploads;

    public $selectedId;
    public $name;
    public $description;
    public $link;
    public $logo;
    public $existingLogo;
    public $status = 'published';
    public $order  = 0;

    public $isEditing  = false;
    public $isCreating = false;

    public $viewingPartner = null;

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function showDetails($id)
    {
        $this->viewingPartner = MediaPartner::findOrFail($id);
    }

    public function closeDetails()
    {
        $this->viewingPartner = null;
    }

    public function edit($id)
    {
        $p = MediaPartner::findOrFail($id);
        $this->viewingPartner = null;
        $this->selectedId   = $p->id;
        $this->name         = $p->name;
        $this->description  = $p->description;
        $this->link         = $p->link;
        $this->existingLogo = $p->logo;
        $this->logo         = null;
        $this->status       = $p->status;
        $this->order        = $p->sort_order ?? 0;
        $this->isEditing    = true;
    }

    public function save()
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link'        => 'nullable|url|max:255',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB
            'status'      => 'required|in:draft,published',
            'order'       => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($this->name);

        $data = [
            'name'        => $this->name,
            'description' => $this->description,
            'link'        => $this->link,
            'status'      => $this->status,
            'sort_order'  => $this->order ?? 0,
            'updated_by'  => auth()->id(),
        ];

        if ($this->logo) {
            $filename = 'media-partners/' . Str::random(20) . '.' . $this->logo->getClientOriginalExtension();
            $this->logo->storeAs('', $filename, 'public');
            $data['logo'] = $filename;
        }

        if ($this->isCreating) {
            if (MediaPartner::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }
            $data['slug']       = $slug;
            $data['created_by'] = auth()->id();
            $p = MediaPartner::create($data);
            ActivityLogger::log('create', "Menambahkan media partner: {$p->name}", $p);
            session()->flash('success', "Media partner \"{$p->name}\" berhasil ditambahkan.");
        } else {
            $p = MediaPartner::findOrFail($this->selectedId);
            if (MediaPartner::where('slug', $slug)->where('id', '!=', $p->id)->exists()) {
                $slug .= '-' . time();
            }
            $data['slug'] = $slug;
            if (isset($data['logo']) && $p->logo) {
                Storage::disk('public')->delete($p->logo);
            }
            $p->update($data);
            ActivityLogger::log('update', "Memperbarui media partner: {$p->name}", $p);
            session()->flash('success', "Media partner \"{$p->name}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $p = MediaPartner::findOrFail($id);
        if ($p->logo) {
            Storage::disk('public')->delete($p->logo);
        }
        ActivityLogger::log('delete', "Menghapus media partner: {$p->name}", $p);
        $p->delete();
        session()->flash('success', 'Media partner berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->name = $this->description = $this->link = '';
        $this->logo = null;
        $this->existingLogo = null;
        $this->status = 'published';
        $this->order = 0;
    }

    public function render()
    {
        $partners = MediaPartner::orderBy('sort_order')->get();

        return view('livewire.admin.media-partner-manager', compact('partners'))
            ->layout('layouts.admin', ['title' => 'Kelola Media Partner']);
    }
}
