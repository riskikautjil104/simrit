<?php

namespace App\Livewire\Admin;

use App\Models\QuizRegistration;
use App\Models\User;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class QuizRegistrationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $rejectingId = null;
    public $rejection_reason = '';

    public $generatedPassword = null;
    public $generatedEmail = null;
    public $generatedName = null;

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

        // Query all questions and join the participant's answers
        $this->participantAnswers = QuizQuestion::orderBy('sort_order')
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

        return view('livewire.admin.quiz-registration-manager', compact('registrations'))
            ->layout('layouts.admin', ['title' => 'Kelola Pendaftar Lomba']);
    }
}
