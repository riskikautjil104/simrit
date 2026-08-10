<?php

namespace App\Livewire\Admin;

use App\Models\Quiz;
use Livewire\Component;

class QuizManager extends Component
{
    // ── List & Search ─────────────────────────────────────────────────────
    public string $search = '';

    // ── Form ─────────────────────────────────────────────────────────────
    public bool   $showForm    = false;
    public ?int   $editingId   = null;

    public string $name              = '';
    public string $description       = '';
    public string $start_at          = '';
    public string $end_at            = '';
    public int    $duration_minutes  = 60;
    public string $time_per_question = '';   // blank = tidak terbatas
    public string $status            = 'draft';

    // ── Validation ────────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'time_per_question'=> 'nullable|integer|min:5|max:600',
            'status'           => 'required|in:draft,active,finished',
        ];
    }

    // ── Open form ─────────────────────────────────────────────────────────
    public function create(): void
    {
        $this->reset(['editingId','name','description','start_at','end_at','time_per_question']);
        $this->duration_minutes = 60;
        $this->status           = 'draft';
        $this->showForm         = true;
    }

    public function edit(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        $this->editingId          = $id;
        $this->name               = $quiz->name;
        $this->description        = $quiz->description ?? '';
        $this->start_at           = $quiz->start_at?->format('Y-m-d\TH:i') ?? '';
        $this->end_at             = $quiz->end_at?->format('Y-m-d\TH:i') ?? '';
        $this->duration_minutes   = $quiz->duration_minutes;
        $this->time_per_question  = $quiz->time_per_question ?? '';
        $this->status             = $quiz->status;
        $this->showForm           = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['time_per_question'] = $data['time_per_question'] ?: null;
        $data['start_at']          = $data['start_at'] ?: null;
        $data['end_at']            = $data['end_at'] ?: null;
        $data['created_by']        = auth()->id();

        if ($this->editingId) {
            Quiz::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kuis berhasil diperbarui.');
        } else {
            Quiz::create($data);
            session()->flash('success', 'Kuis baru berhasil dibuat.');
        }

        $this->showForm = false;
        $this->reset(['editingId','name','description','start_at','end_at','time_per_question']);
    }

    public function delete(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        // Prevent deleting if there are participants
        if ($quiz->registrations()->exists()) {
            session()->flash('error', 'Tidak bisa menghapus kuis yang sudah memiliki peserta.');
            return;
        }
        $quiz->delete();
        session()->flash('success', 'Kuis berhasil dihapus.');
    }

    public function toggleStatus(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        $next = match($quiz->status) {
            'draft'    => 'active',
            'active'   => 'finished',
            'finished' => 'draft',
            default    => 'draft',
        };
        $quiz->update(['status' => $next]);
        session()->flash('success', "Status kuis diubah ke \"{$next}\".");
    }

    public function render()
    {
        $quizzes = Quiz::withCount(['questions', 'registrations'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        return view('livewire.admin.quiz-manager', compact('quizzes'))
            ->layout('layouts.admin', ['title' => 'Kelola Kuis']);
    }
}
