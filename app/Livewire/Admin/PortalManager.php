<?php

namespace App\Livewire\Admin;

use App\Models\Portal;
use App\Services\ActivityLogger;
use Livewire\Component;

class PortalManager extends Component
{
    public $selectedId;
    public $name;
    public $description;
    public $link;
    public $icon;
    public $status = 'published';
    public $order = 0;

    public $isEditing = false;
    public $isCreating = false;

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $portal = Portal::findOrFail($id);
        $this->selectedId = $portal->id;
        $this->name = $portal->name;
        $this->description = $portal->description;
        $this->link = $portal->link;
        $this->icon = $portal->icon;
        $this->status = $portal->status;
        $this->order = $portal->sort_order ?? 0;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'link' => 'required|url|max:255',
            'icon' => 'nullable|string|max:50',
            'status' => 'required|in:draft,published',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'link' => $this->link,
            'icon' => $this->icon,
            'status' => $this->status,
            'sort_order' => $this->order ?? 0,
            'updated_by' => auth()->id(),
        ];

        if ($this->isCreating) {
            $data['created_by'] = auth()->id();
            $portal = Portal::create($data);
            ActivityLogger::log('create', "Menambahkan portal: {$portal->name}", $portal);
            session()->flash('success', "Portal \"{$portal->name}\" berhasil ditambahkan.");
        } else {
            $portal = Portal::findOrFail($this->selectedId);
            $portal->update($data);
            ActivityLogger::log('update', "Memperbarui portal: {$portal->name}", $portal);
            session()->flash('success', "Portal \"{$portal->name}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $portal = Portal::findOrFail($id);
        ActivityLogger::log('delete', "Menghapus portal: {$portal->name}", $portal);
        $portal->delete();
        session()->flash('success', 'Portal berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->isCreating = false;
        $this->selectedId = null;
        $this->name = $this->description = $this->link = $this->icon = '';
        $this->status = 'published';
        $this->order = 0;
    }

    public function render()
    {
        $portals = Portal::orderBy('sort_order')->get();

        return view('livewire.admin.portal-manager', compact('portals'))
            ->layout('layouts.admin', ['title' => 'Kelola Portal']);
    }
}
