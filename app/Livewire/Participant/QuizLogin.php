<?php

namespace App\Livewire\Participant;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizLogin extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();

            if ($user->role !== 'participant') {
                Auth::logout();
                session()->flash('error', 'Hanya akun peserta lomba yang diizinkan masuk ke portal ini.');
                return;
            }

            if (!$user->is_active) {
                Auth::logout();
                session()->flash('error', 'Akun Anda dinonaktifkan.');
                return;
            }

            // Regenerate session
            session()->regenerate();

            return redirect()->route('participant.dashboard');
        }

        session()->flash('error', 'Kredensial yang Anda masukkan salah.');
    }

    public function render()
    {
        return view('livewire.participant.quiz-login')
            ->layout('layouts.public', ['title' => 'Login Peserta Lomba Cerdas Cermat']);
    }
}
