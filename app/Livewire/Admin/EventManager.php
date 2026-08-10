<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventManager extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';

    // Form fields
    public $selectedEventId;
    public $title;
    public $description;
    public $location;
    public $starts_at;
    public $ends_at;
    public $status = 'draft';
    public $cover_image;
    public $existingCoverImage;

    public $isEditing = false;
    public $isCreating = false;

    public function updatingSearch()
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
        $event = Event::findOrFail($id);
        $this->selectedEventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->location = $event->location;
        $this->starts_at = $event->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $event->ends_at ? $event->ends_at->format('Y-m-d\TH:i') : null;
        $this->status = $event->status;
        $this->existingCoverImage = $event->cover_image;
        $this->cover_image = null;

        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'starts_at'   => 'required|date',
            'ends_at'     => 'nullable|date|after_or_equal:starts_at',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $slug = Str::slug($this->title);

        if ($this->isCreating) {
            // Check unique slug
            $count = Event::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }

            $data = [
                'title'       => $this->title,
                'slug'        => $slug,
                'description' => $this->description,
                'location'    => $this->location,
                'starts_at'   => $this->starts_at,
                'ends_at'     => $this->ends_at ?: null,
                'status'      => $this->status,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ];

            if ($this->cover_image) {
                $filename = 'events/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }

            $event = Event::create($data);

            ActivityLogger::log('create', "Membuat agenda baru: {$event->title}", $event);
            session()->flash('success', "Agenda {$event->title} berhasil dibuat.");
        } else {
            $event = Event::findOrFail($this->selectedEventId);

            // Check unique slug
            $count = Event::where('slug', $slug)->where('id', '!=', $event->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }

            $data = [
                'title'       => $this->title,
                'slug'        => $slug,
                'description' => $this->description,
                'location'    => $this->location,
                'starts_at'   => $this->starts_at,
                'ends_at'     => $this->ends_at ?: null,
                'status'      => $this->status,
                'updated_by'  => auth()->id(),
            ];

            if ($this->cover_image) {
                if ($event->cover_image) {
                    Storage::disk('public')->delete($event->cover_image);
                }
                $filename = 'events/' . Str::random(20) . '.' . $this->cover_image->getClientOriginalExtension();
                $this->cover_image->storeAs('', $filename, 'public');
                $data['cover_image'] = $filename;
            }

            $event->update($data);

            ActivityLogger::log('update', "Memperbarui agenda: {$event->title}", $event);
            session()->flash('success', "Agenda {$event->title} berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        ActivityLogger::log('delete', "Menghapus agenda: {$event->title}", $event);
        session()->flash('success', "Agenda {$event->title} berhasil dihapus.");
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->isCreating = false;
        $this->selectedEventId = null;
        $this->title = '';
        $this->description = '';
        $this->location = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->status = 'draft';
        $this->cover_image = null;
        $this->existingCoverImage = null;
    }

    public function render()
    {
        $query = Event::latest();

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('location', 'like', '%'.$this->search.'%');
        }

        $events = $query->paginate(10);

        return view('livewire.admin.event-manager', compact('events'))
            ->layout('layouts.admin', ['title' => 'Kelola Agenda Kegiatan']);
    }
}
