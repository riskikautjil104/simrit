<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceManager extends Component
{
    use WithFileUploads;

    public $selectedId;
    public $title;
    public $short_description;
    public $content;
    public $icon;
    public $image;
    public $existingImage;
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
        $s = Service::findOrFail($id);
        $this->selectedId        = $s->id;
        $this->title             = $s->title;
        $this->short_description = $s->short_description;
        $this->content           = $s->content;
        $this->icon              = $s->icon;
        $this->existingImage     = $s->image;
        $this->image             = null;
        $this->status            = $s->status;
        $this->order             = $s->order ?? 0;
        $this->isEditing         = true;
    }

    public function save()
    {
        $this->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'nullable|string',
            'icon'              => 'nullable|string|max:50',
            'image'             => 'nullable|image|max:5120',
            'status'            => 'required|in:draft,published,archived',
            'order'             => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($this->title);

        if ($this->isCreating) {
            if (Service::where('slug', $slug)->count()) {
                $slug .= '-' . time();
            }
            $data = [
                'title'             => $this->title,
                'slug'              => $slug,
                'short_description' => $this->short_description,
                'content'           => $this->content,
                'icon'              => $this->icon,
                'status'            => $this->status,
                'sort_order'        => $this->order ?? 0,
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
            ];
            if ($this->image) {
                $filename = 'services/' . Str::random(20) . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('', $filename, 'public');
                $data['image'] = $filename;
            }
            $svc = Service::create($data);
            ActivityLogger::log('create', "Menambahkan layanan IT: {$svc->title}", $svc);
            session()->flash('success', "Layanan \"{$svc->title}\" berhasil ditambahkan.");
        } else {
            $svc = Service::findOrFail($this->selectedId);
            if (Service::where('slug', $slug)->where('id', '!=', $svc->id)->count()) {
                $slug .= '-' . time();
            }
            $data = [
                'title'             => $this->title,
                'slug'              => $slug,
                'short_description' => $this->short_description,
                'content'           => $this->content,
                'icon'              => $this->icon,
                'status'            => $this->status,
                'sort_order'        => $this->order ?? 0,
                'updated_by'        => auth()->id(),
            ];
            if ($this->image) {
                if ($svc->image) {
                    Storage::disk('public')->delete($svc->image);
                }
                $filename = 'services/' . Str::random(20) . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('', $filename, 'public');
                $data['image'] = $filename;
            }
            $svc->update($data);
            ActivityLogger::log('update', "Memperbarui layanan: {$svc->title}", $svc);
            session()->flash('success', "Layanan \"{$svc->title}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $svc = Service::findOrFail($id);
        if ($svc->image) {
            Storage::disk('public')->delete($svc->image);
        }
        ActivityLogger::log('delete', "Menghapus layanan: {$svc->title}", $svc);
        $svc->delete();
        session()->flash('success', 'Layanan berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->title = $this->short_description = $this->content = $this->icon = '';
        $this->image = null;
        $this->existingImage = null;
        $this->status = 'published';
        $this->order = 0;
    }

    public function render()
    {
        $services = Service::orderBy('sort_order')->get();

        return view('livewire.admin.service-manager', compact('services'))
            ->layout('layouts.admin', ['title' => 'Kelola Layanan IT']);
    }
}
