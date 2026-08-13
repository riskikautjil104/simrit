<?php

namespace App\Livewire\Admin;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocumentManager extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $categoryFilter = '';

    public $selectedId;
    public $document_category_id;
    public $title;
    public $description;
    public $status = 'published';
    public $file;
    public $existingFilePath;
    public $existingFilename;

    public $isEditing = false;
    public $isCreating = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($id)
    {
        $doc = Document::findOrFail($id);
        $this->selectedId             = $doc->id;
        $this->document_category_id   = $doc->document_category_id;
        $this->title                  = $doc->title;
        $this->description            = $doc->description;
        $this->status                 = $doc->status;
        $this->existingFilePath       = $doc->file_path;
        $this->existingFilename       = $doc->original_filename;
        $this->file                   = null;
        $this->isEditing              = true;
    }

    public function save()
    {
        $rules = [
            'document_category_id' => 'nullable|exists:document_categories,id',
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string',
            'status'               => 'required|in:draft,published,archived',
            'file'                 => ($this->isCreating ? 'required' : 'nullable').'|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip',
        ];
        $this->validate($rules);

        $slug = Str::slug($this->title);
        if (Document::where('slug', $slug)->when($this->selectedId, fn ($q) => $q->where('id', '!=', $this->selectedId))->exists()) {
            $slug .= '-' . time();
        }

        if ($this->isCreating) {
            $originalName = $this->file->getClientOriginalName();
            $ext          = $this->file->getClientOriginalExtension();
            $filename     = 'documents/' . Str::random(20) . '.' . $ext;
            $this->file->storeAs('', $filename, 'public');

            $doc = Document::create([
                'document_category_id' => $this->document_category_id ?: null,
                'title'                => $this->title,
                'slug'                 => $slug,
                'description'          => $this->description,
                'file_path'            => $filename,
                'original_filename'    => $originalName,
                'file_size'            => $this->file->getSize(),
                'mime_type'            => $this->file->getMimeType(),
                'status'               => $this->status,
                'uploaded_by'          => auth()->id(),
                'published_at'         => $this->status === 'published' ? now() : null,
            ]);
            ActivityLogger::log('create', "Mengunggah dokumen: {$doc->title}", $doc);
            session()->flash('success', "Dokumen \"{$doc->title}\" berhasil diunggah.");
        } else {
            $doc = Document::findOrFail($this->selectedId);
            $data = [
                'document_category_id' => $this->document_category_id ?: null,
                'title'                => $this->title,
                'slug'                 => $slug,
                'description'          => $this->description,
                'status'               => $this->status,
            ];

            if ($this->file) {
                Storage::disk('public')->delete($doc->file_path);
                $originalName = $this->file->getClientOriginalName();
                $ext          = $this->file->getClientOriginalExtension();
                $filename     = 'documents/' . Str::random(20) . '.' . $ext;
                $this->file->storeAs('', $filename, 'public');
                $data['file_path']         = $filename;
                $data['original_filename'] = $originalName;
                $data['file_size']         = $this->file->getSize();
                $data['mime_type']         = $this->file->getMimeType();
            }

            $doc->update($data);
            ActivityLogger::log('update', "Memperbarui dokumen: {$doc->title}", $doc);
            session()->flash('success', "Dokumen \"{$doc->title}\" berhasil diperbarui.");
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $doc = Document::findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
        ActivityLogger::log('delete', "Menghapus dokumen: {$doc->title}", $doc);
        $doc->delete();
        session()->flash('success', 'Dokumen berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId = null;
        $this->document_category_id = '';
        $this->title = $this->description = '';
        $this->status = 'published';
        $this->file = null;
        $this->existingFilePath = $this->existingFilename = null;
    }

    public function render()
    {
        $query = Document::with('category')->latest();
        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }
        if ($this->categoryFilter) {
            $query->where('document_category_id', $this->categoryFilter);
        }
        $documents = $query->paginate(12);
        $categories = DocumentCategory::all();

        return view('livewire.admin.document-manager', compact('documents', 'categories'))
            ->layout('layouts.admin', ['title' => 'Kelola Dokumen']);
    }
}
