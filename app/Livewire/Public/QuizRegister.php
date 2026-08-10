<?php

namespace App\Livewire\Public;

use App\Models\QuizRegistration;
use Livewire\Component;

class QuizRegister extends Component
{
    public $name;
    public $position;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;

    public $successMessage = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:quiz_registrations,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        QuizRegistration::create([
            'name' => $this->name,
            'position' => $this->position,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
            'status' => 'pending',
        ]);

        $this->successMessage = 'Pendaftaran Anda berhasil dikirim! Panitia akan melakukan verifikasi dan menyetujui pendaftaran Anda. Setelah disetujui, Anda dapat langsung login menggunakan password yang telah Anda masukkan.';
        $this->reset(['name', 'position', 'email', 'phone', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.public.quiz-register')
            ->layout('layouts.public', ['title' => 'Pendaftaran Lomba Cerdas Cermat 17 Agustus']);
    }
}
