<?php

namespace App\Livewire\Admin;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Services\ActivityLogger;
use App\Services\QuizQuestionExcelService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QuizQuestionManager extends Component
{
    use WithPagination, WithFileUploads;

    // ── Filters ───────────────────────────────────────────────────────────
    public string  $search       = '';
    public ?int    $filterQuizId = null;

    // ── Form state ────────────────────────────────────────────────────────
    public $isEditing  = false;
    public $isCreating = false;
    public $selectedId;

    public ?int    $quiz_id        = null;
    public string  $question       = '';
    public string  $option_a       = '';
    public string  $option_b       = '';
    public string  $option_c       = '';
    public string  $option_d       = '';
    public string  $correct_answer = 'a';
    public int     $points         = 1;
    public string  $status         = 'active';
    public int     $sort_order     = 0;
    public string  $time_limit     = '';   // blank = pakai default kuis / tak terbatas

    public $image;
    public $existingImage;

    public bool   $showImportModal = false;
    public ?int   $importQuizId    = null;
    public $importFile;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterQuizId() { $this->resetPage(); }

    // ── Open form ─────────────────────────────────────────────────────────
    public function create(): void
    {
        $this->resetForm();
        $this->quiz_id    = $this->filterQuizId;  // pre-fill dengan kuis aktif di filter
        $this->isCreating = true;
    }

    public function openImport(): void
    {
        $this->importQuizId    = $this->filterQuizId;
        $this->importFile      = null;
        $this->showImportModal = true;
    }

    public function closeImport(): void
    {
        $this->showImportModal = false;
        $this->importFile      = null;
    }

    public function importQuestions(QuizQuestionExcelService $excelService): void
    {
        $this->validate([
            'importFile'   => 'required|file|mimes:xlsx,xls|max:5120',
            'importQuizId' => 'nullable|exists:quizzes,id',
        ], [
            'importFile.required' => 'Pilih file Excel terlebih dahulu.',
            'importFile.mimes'    => 'File harus berformat .xlsx atau .xls.',
        ]);

        $result = $excelService->import(
            $this->importFile,
            $this->importQuizId ?: null,
            auth()->id()
        );

        if ($result['imported'] > 0) {
            ActivityLogger::log(
                'create',
                "Import {$result['imported']} soal kuis dari Excel",
                null
            );
        }

        if (! empty($result['errors'])) {
            session()->flash('import_errors', $result['errors']);
        }

        if ($result['imported'] > 0) {
            session()->flash('success', "{$result['imported']} soal berhasil diimpor dari Excel.");
        } elseif (empty($result['errors'])) {
            session()->flash('error', 'Tidak ada soal yang berhasil diimpor.');
        } else {
            session()->flash('error', 'Import gagal. Periksa error di bawah.');
        }

        $this->closeImport();
    }

    public function edit(int $id): void
    {
        $q = QuizQuestion::findOrFail($id);
        $this->selectedId    = $q->id;
        $this->quiz_id       = $q->quiz_id;
        $this->question      = $q->question;
        $this->option_a      = $q->option_a;
        $this->option_b      = $q->option_b;
        $this->option_c      = $q->option_c;
        $this->option_d      = $q->option_d;
        $this->correct_answer = $q->correct_answer;
        $this->points        = $q->points;
        $this->status        = $q->status;
        $this->sort_order    = $q->sort_order;
        $this->time_limit    = $q->time_limit ?? '';
        $this->existingImage = $q->image_path;
        $this->image         = null;
        $this->isEditing     = true;
    }

    public function save(): void
    {
        $this->validate([
            'quiz_id'        => 'nullable|exists:quizzes,id',
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
            'points'         => 'required|integer|min:1',
            'status'         => 'required|in:active,draft',
            'sort_order'     => 'required|integer|min:0',
            'time_limit'     => 'nullable|integer|min:5|max:600',
            'image'          => 'nullable|image|max:5120',
        ]);

        $data = [
            'quiz_id'        => $this->quiz_id ?: null,
            'question'       => $this->question,
            'option_a'       => $this->option_a,
            'option_b'       => $this->option_b,
            'option_c'       => $this->option_c,
            'option_d'       => $this->option_d,
            'correct_answer' => $this->correct_answer,
            'points'         => $this->points,
            'status'         => $this->status,
            'sort_order'     => $this->sort_order,
            'time_limit'     => $this->time_limit !== '' ? (int) $this->time_limit : null,
        ];

        if ($this->image) {
            $filename = 'quiz_questions/' . Str::random(20) . '.' . $this->image->getClientOriginalExtension();
            $this->image->storeAs('', $filename, 'public');
            $data['image_path'] = $filename;
        }

        if ($this->isCreating) {
            $data['created_by'] = auth()->id();
            $q = QuizQuestion::create($data);
            ActivityLogger::log('create', "Membuat soal kuis: ID {$q->id}", $q);
            session()->flash('success', 'Soal berhasil dibuat.');
        } else {
            $q = QuizQuestion::findOrFail($this->selectedId);
            if ($this->image && $q->image_path) {
                Storage::disk('public')->delete($q->image_path);
            }
            $q->update($data);
            ActivityLogger::log('update', "Memperbarui soal kuis: ID {$q->id}", $q);
            session()->flash('success', 'Soal berhasil diperbarui.');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $q = QuizQuestion::findOrFail($id);
        if ($q->image_path) {
            Storage::disk('public')->delete($q->image_path);
        }
        ActivityLogger::log('delete', "Menghapus soal kuis: ID {$q->id}", $q);
        $q->delete();
        session()->flash('success', 'Soal berhasil dihapus.');
    }

    public function reorder(int $id, string $direction): void
    {
        $q = QuizQuestion::findOrFail($id);
        if ($direction === 'up') {
            $q->decrement('sort_order');
        } elseif ($direction === 'down') {
            $q->increment('sort_order');
        }
    }

    public function resetForm(): void
    {
        $this->isEditing = $this->isCreating = false;
        $this->selectedId     = null;
        $this->quiz_id        = null;
        $this->question       = '';
        $this->option_a       = '';
        $this->option_b       = '';
        $this->option_c       = '';
        $this->option_d       = '';
        $this->correct_answer = 'a';
        $this->points         = 1;
        $this->status         = 'active';
        $this->sort_order     = 0;
        $this->time_limit     = '';
        $this->image          = null;
        $this->existingImage  = null;
        $this->showImportModal = false;
        $this->importFile      = null;
    }

    public function render()
    {
        $quizzes = Quiz::orderBy('name')->get();

        $questions = QuizQuestion::with('quiz')
            ->when($this->search, fn($q) => $q->where('question', 'like', "%{$this->search}%"))
            ->when($this->filterQuizId, fn($q) => $q->where('quiz_id', $this->filterQuizId))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15);

        return view('livewire.admin.quiz-question-manager', compact('questions', 'quizzes'))
            ->layout('layouts.admin', ['title' => 'Kelola Soal Kuis']);
    }
}
