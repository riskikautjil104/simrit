<?php

namespace App\Livewire\Participant;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\QuizRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizDashboard extends Component
{
    public $questions;
    public $registration;
    public $activeQuiz;

    public array  $answers          = [];   // question_id => selected_answer
    public int    $currentIndex     = 0;
    public bool   $confirmingSubmit = false;
    public bool   $finished         = false;
    public int    $score            = 0;
    public int    $correctCount     = 0;

    // Timer state (managed by JS / poll)
    public int    $secondsLeft      = 0;    // countdown for current question
    public bool   $quizNotStarted   = false;
    public int    $secondsToStart   = 0;    // countdown until quiz opens
    public bool   $quizEnded        = false;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'participant' || ! $user->is_active) {
            Auth::logout();
            $this->redirect(route('participant.login'), navigate: false);
            return;
        }

        $this->registration = QuizRegistration::where('user_id', $user->id)->first();

        if (! $this->registration) {
            Auth::logout();
            $this->redirect(route('participant.login'), navigate: false);
            return;
        }

        // Determine the quiz this participant is linked to, or fallback to the first active quiz
        $this->activeQuiz = $this->registration->quiz
            ?? Quiz::active()->latest()->first();

        $this->evaluateQuizState();

        if ($this->finished || $this->quizNotStarted || $this->quizEnded) {
            return;
        }

        $this->loadQuestions();

        // Already finished
        if ($this->registration->finished_at) {
            $this->finished = true;
            $this->calculateResults();
        }
    }

    // ── Quiz state evaluation ──────────────────────────────────────────────

    private function evaluateQuizState(): void
    {
        if (! $this->activeQuiz) {
            return;   // no quiz linked → allow legacy mode (all active questions)
        }

        $now = now();

        if ($this->activeQuiz->start_at && $now->lt($this->activeQuiz->start_at)) {
            $this->quizNotStarted  = true;
            $this->secondsToStart  = (int) $now->diffInSeconds($this->activeQuiz->start_at);
            return;
        }

        if ($this->activeQuiz->end_at && $now->gt($this->activeQuiz->end_at)) {
            $this->quizEnded = true;
            // Auto-submit if not yet finished
            if (! $this->registration->finished_at) {
                $this->registration->update(['finished_at' => $this->activeQuiz->end_at]);
                $this->finished = true;
                $this->calculateResults();
            }
        }
    }

    private function loadQuestions(): void
    {
        $user = Auth::user();

        if ($this->activeQuiz) {
            $this->questions = QuizQuestion::with('quiz')
                ->where('quiz_id', $this->activeQuiz->id)
                ->active()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        } else {
            // Legacy: no quiz session, show all active questions
            $this->questions = QuizQuestion::active()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        // Load existing answers
        $existingAnswers = QuizAnswer::where('user_id', $user->id)->get();
        foreach ($existingAnswers as $ans) {
            $this->answers[$ans->quiz_question_id] = $ans->selected_answer;
        }

        // Initialize secondsLeft for the first question
        $this->refreshSecondsLeft();
    }

    // ── Timer ──────────────────────────────────────────────────────────────

    /** Called by wire:poll every second to decrement countdown */
    public function tick(): void
    {
        if ($this->finished || $this->quizNotStarted) {
            // Re-evaluate if quiz has just started
            if ($this->quizNotStarted) {
                $this->evaluateQuizState();
                if (! $this->quizNotStarted) {
                    $this->loadQuestions();
                }
            }
            return;
        }

        // Check quiz end_at
        if ($this->activeQuiz?->end_at && now()->gt($this->activeQuiz->end_at)) {
            $this->submitQuiz();
            return;
        }

        $limit = $this->currentEffectiveTimeLimit();
        if ($limit === null) return;   // unlimited

        if ($this->secondsLeft > 0) {
            $this->secondsLeft--;
        } else {
            // Time's up for this question → auto-advance (no answer = skip)
            $this->autoAdvance();
        }
    }

    private function refreshSecondsLeft(): void
    {
        $limit = $this->currentEffectiveTimeLimit();
        $this->secondsLeft = $limit ?? 0;
    }

    private function currentEffectiveTimeLimit(): ?int
    {
        if (! $this->questions || $this->questions->isEmpty()) return null;
        $q = $this->questions[$this->currentIndex] ?? null;
        return $q?->effective_time_limit;
    }

    private function autoAdvance(): void
    {
        if ($this->currentIndex < ($this->questions->count() - 1)) {
            $this->currentIndex++;
            $this->refreshSecondsLeft();
        } else {
            // Last question — auto-submit
            $this->submitQuiz();
        }
    }

    // ── User actions ──────────────────────────────────────────────────────

    public function selectAnswer(int $questionId, string $option): void
    {
        if ($this->finished) return;

        $this->answers[$questionId] = $option;

        $question  = QuizQuestion::findOrFail($questionId);
        $isCorrect = strtolower($option) === strtolower($question->correct_answer);

        QuizAnswer::updateOrCreate(
            ['user_id' => Auth::id(), 'quiz_question_id' => $questionId],
            [
                'quiz_id'         => $this->activeQuiz?->id,
                'selected_answer' => $option,
                'is_correct'      => $isCorrect,
                'answered_at'     => now(),
            ]
        );
    }

    public function selectIndex(int $index): void
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentIndex = $index;
            $this->refreshSecondsLeft();
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
            $this->refreshSecondsLeft();
        }
    }

    public function prevQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->refreshSecondsLeft();
        }
    }

    public function startSubmit(): void  { $this->confirmingSubmit = true; }
    public function cancelSubmit(): void { $this->confirmingSubmit = false; }

    public function submitQuiz(): void
    {
        if ($this->finished) return;

        $this->registration->update(['finished_at' => now()]);
        $this->finished         = true;
        $this->confirmingSubmit = false;
        $this->calculateResults();

        session()->flash('success', 'Kuis berhasil dikirim. Terima kasih atas partisipasi Anda!');
    }

    protected function calculateResults(): void
    {
        $userId           = Auth::id();
        $this->correctCount = QuizAnswer::where('user_id', $userId)->where('is_correct', true)->count();
        $this->score        = (int) QuizAnswer::where('user_id', $userId)
            ->where('is_correct', true)
            ->join('quiz_questions', 'quiz_answers.quiz_question_id', '=', 'quiz_questions.id')
            ->sum('quiz_questions.points');
    }

    public function render()
    {
        return view('livewire.participant.quiz-dashboard')
            ->layout('layouts.public', ['title' => 'Portal Ujian Cerdas Cermat 17-an']);
    }
}
