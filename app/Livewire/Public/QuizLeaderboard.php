<?php

namespace App\Livewire\Public;

use App\Models\QuizQuestion;
use App\Models\QuizRegistration;
use App\Models\User;
use Livewire\Component;

class QuizLeaderboard extends Component
{
    public string $lastRefreshed = '';
    public int $totalQuestions   = 0;

    public function mount(): void
    {
        $this->lastRefreshed  = now()->format('H:i:s');
        $this->totalQuestions = QuizQuestion::active()->count();
    }

    /** Called automatically by wire:poll */
    public function refresh(): void
    {
        $this->lastRefreshed  = now()->format('H:i:s');
        $this->totalQuestions = QuizQuestion::active()->count();
    }

    public function render()
    {
        $leaders = User::query()
            ->where('role', 'participant')
            ->where('is_active', true)
            ->withCount(['quizAnswers as correct_count' => fn($q) => $q->where('is_correct', true)])
            ->withSum([
                'quizAnswers as total_score' => fn($q) => $q
                    ->where('is_correct', true)
                    ->join('quiz_questions', 'quiz_answers.quiz_question_id', '=', 'quiz_questions.id'),
            ], 'quiz_questions.points')
            ->with(['quizRegistration:id,user_id,position,finished_at'])
            ->get()
            ->map(function ($user) {
                $user->total_score   = (int) ($user->total_score ?? 0);
                $user->correct_count = (int) ($user->correct_count ?? 0);
                // Use quiz registration finished_at as canonical completion time
                $user->finished_at = optional($user->quizRegistration)->finished_at;
                return $user;
            })
            ->filter(fn($u) => $u->correct_count > 0 || optional($u->quizRegistration)->finished_at !== null)
            ->sortByDesc(function ($user) {
                // Primary: highest score | Secondary: earliest finish time
                $timePenalty = $user->finished_at
                    ? $user->finished_at->timestamp
                    : 9_999_999_999;
                return sprintf('%010d.%010d', $user->total_score, 9_999_999_999 - $timePenalty);
            })
            ->values();

        return view('livewire.public.quiz-leaderboard', [
            'leaders'        => $leaders,
            'totalQuestions' => $this->totalQuestions,
            'lastRefreshed'  => $this->lastRefreshed,
        ])->layout('layouts.public', ['title' => 'Papan Peringkat Lomba Cerdas Cermat 17-an']);
    }
}
