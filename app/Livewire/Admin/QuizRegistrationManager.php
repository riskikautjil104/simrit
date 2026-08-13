<?php

namespace App\Livewire\Admin;

use App\Models\QuizRegistration;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class QuizRegistrationManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $rejectingId = null;
    public $rejection_reason = '';

    public $generatedPassword = null;
    public $generatedEmail = null;
    public $generatedName = null;

    // CSV Import properties
    public $csvFile;
    public $selectedQuizId = '';
    public $showImportModal = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function approve($id)
    {
        $reg = QuizRegistration::findOrFail($id);

        if ($reg->status !== 'pending') {
            session()->flash('error', 'Pendaftaran ini sudah diproses.');
            return;
        }

        // Check if user already exists
        $user = User::where('email', $reg->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $reg->name,
                'email' => $reg->email,
                'password' => $reg->password, // Already hashed during registration
                'role' => 'participant',
                'is_active' => true,
            ]);
        } else {
            // If user already exists but role is participant, update password
            $user->update([
                'password' => $reg->password,
                'role' => 'participant',
                'is_active' => true,
            ]);
        }

        $reg->update([
            'status' => 'approved',
            'user_id' => $user->id,
        ]);

        ActivityLogger::log('update', "Menyetujui pendaftaran lomba: {$reg->name}", $reg);

        session()->flash('success', "Pendaftaran \"{$reg->name}\" berhasil disetujui. Akun peserta telah diaktifkan.");
    }

    public function startReject($id)
    {
        $this->rejectingId = $id;
        $this->rejection_reason = '';
    }

    public function submitReject()
    {
        $this->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $reg = QuizRegistration::findOrFail($this->rejectingId);
        $reg->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejection_reason,
        ]);

        ActivityLogger::log('update', "Menolak pendaftaran lomba: {$reg->name} (Alasan: {$this->rejection_reason})", $reg);

        session()->flash('success', "Pendaftaran \"{$reg->name}\" telah ditolak.");
        $this->cancelReject();
    }

    public function cancelReject()
    {
        $this->rejectingId = null;
        $this->rejection_reason = '';
    }

    public $viewingAnswersUserId = null;
    public $viewingParticipantName = '';
    public $participantAnswers = [];

    public function viewAnswers($userId, $name)
    {
        $this->viewingAnswersUserId = $userId;
        $this->viewingParticipantName = $name;

        // Find the quiz session this participant is registered for
        $registration = QuizRegistration::where('user_id', $userId)->first();
        $quizId = $registration ? $registration->quiz_id : null;

        // Query questions belonging only to the participant's registered quiz session
        $query = QuizQuestion::query();
        if ($quizId) {
            $query->where('quiz_id', $quizId);
        }

        $this->participantAnswers = $query->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function($q) use ($userId) {
                $ans = QuizAnswer::where('user_id', $userId)
                    ->where('quiz_question_id', $q->id)
                    ->first();
                
                return [
                    'question' => $q->question,
                    'option_a' => $q->option_a,
                    'option_b' => $q->option_b,
                    'option_c' => $q->option_c,
                    'option_d' => $q->option_d,
                    'correct_answer' => $q->correct_answer,
                    'selected_answer' => $ans ? $ans->selected_answer : null,
                    'is_correct' => $ans ? $ans->is_correct : false,
                ];
            })
            ->toArray();
    }

    public function closeAnswers()
    {
        $this->viewingAnswersUserId = null;
        $this->viewingParticipantName = '';
        $this->participantAnswers = [];
    }

    public function closeCredentials()
    {
        $this->generatedName = null;
        $this->generatedEmail = null;
        $this->generatedPassword = null;
    }

    public $viewingRegDetails = null;

    public function showDetails($id)
    {
        $this->viewingRegDetails = QuizRegistration::with('user')->findOrFail($id);
    }

    public function closeDetails()
    {
        $this->viewingRegDetails = null;
    }

    public function toggleParticipantActive($id)
    {
        $reg = QuizRegistration::findOrFail($id);
        
        if ($reg->user_id) {
            $user = User::find($reg->user_id);
            if ($user) {
                $user->update([
                    'is_active' => !$user->is_active,
                ]);
                $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
                ActivityLogger::log('update', "Status akun peserta {$reg->name} {$status}", $user);
                session()->flash('success', "Akun peserta \"{$reg->name}\" berhasil {$status}.");
            }
        } else {
            session()->flash('error', 'Akun peserta belum dibuat atau disetujui.');
        }

        // Refresh detail modal if open
        if ($this->viewingRegDetails && $this->viewingRegDetails->id == $id) {
            $this->showDetails($id);
        }
    }

    public function deleteRegistration($id)
    {
        $reg = QuizRegistration::findOrFail($id);
        $name = $reg->name;

        // If there is an associated user, delete it
        if ($reg->user_id) {
            $user = User::find($reg->user_id);
            if ($user) {
                // Delete user's answers first
                QuizAnswer::where('user_id', $user->id)->delete();
                $user->delete();
            }
        }

        $reg->delete();

        ActivityLogger::log('delete', "Menghapus peserta/pendaftaran kuis: {$name}", null);

        session()->flash('success', "Peserta \"{$name}\" berhasil dihapus.");

        if ($this->viewingRegDetails && $this->viewingRegDetails->id == $id) {
            $this->closeDetails();
        }
    }

    public function importCsv()
    {
        $this->validate([
            'selectedQuizId' => 'required|exists:quizzes,id',
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'selectedQuizId.required' => 'Pilih kuis tujuan terlebih dahulu.',
            'csvFile.required' => 'Pilih file CSV terlebih dahulu.',
            'csvFile.mimes' => 'File harus berupa format CSV.',
        ]);

        $path = $this->csvFile->getRealPath();
        $file = fopen($path, 'r');

        // Read header
        $header = fgetcsv($file, 1000, ',');
        if (!$header) {
            session()->flash('error', 'File CSV kosong atau tidak dapat dibaca.');
            fclose($file);
            return;
        }

        // Clean headers (lowercase and trim whitespace/bom)
        $header = array_map(function($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        // Required columns check: name, email
        $nameIdx = array_search('name', $header);
        $emailIdx = array_search('email', $header);
        $positionIdx = array_search('position', $header);
        $phoneIdx = array_search('phone', $header);
        $passwordIdx = array_search('password', $header);

        if ($nameIdx === false || $emailIdx === false) {
            session()->flash('error', 'Header CSV harus memiliki kolom "name" dan "email".');
            fclose($file);
            return;
        }

        $importedCount = 0;
        $skippedCount = 0;

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            if (empty($row) || count($row) < 2) continue;

            $name = trim($row[$nameIdx] ?? '');
            $email = trim($row[$emailIdx] ?? '');
            $position = $positionIdx !== false ? trim($row[$positionIdx] ?? 'Peserta') : 'Peserta';
            $phone = $phoneIdx !== false ? trim($row[$phoneIdx] ?? '-') : '-';
            $plainPassword = $passwordIdx !== false && !empty(trim($row[$passwordIdx] ?? '')) ? trim($row[$passwordIdx]) : 'password123';

            if (empty($name) || empty($email)) {
                $skippedCount++;
                continue;
            }

            // Check duplicate email
            if (User::where('email', $email)->exists() || QuizRegistration::where('email', $email)->exists()) {
                $skippedCount++;
                continue;
            }

            // Create User
            $hashedPassword = Hash::make($plainPassword);
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'participant',
                'is_active' => true,
            ]);

            // Create approved QuizRegistration
            QuizRegistration::create([
                'quiz_id' => $this->selectedQuizId,
                'name' => $name,
                'email' => $email,
                'position' => $position,
                'phone' => $phone,
                'password' => $hashedPassword,
                'status' => 'approved',
                'user_id' => $user->id,
            ]);

            $importedCount++;
        }

        fclose($file);

        ActivityLogger::log('create', "Mengimpor {$importedCount} peserta kuis dari CSV", null);

        session()->flash('success', "Berhasil mengimpor {$importedCount} peserta. (Dilewati: {$skippedCount})");
        $this->reset(['csvFile', 'selectedQuizId', 'showImportModal']);
    }

    public function render()
    {
        $query = QuizRegistration::with('user')->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('position', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $registrations = $query->paginate(15);
        $quizzes = Quiz::orderBy('name')->get();

        return view('livewire.admin.quiz-registration-manager', compact('registrations', 'quizzes'))
            ->layout('layouts.admin', ['title' => 'Kelola Pendaftar Lomba']);
    }
}
