<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\QuizRegistration;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_register_for_quiz()
    {
        Livewire::test(\App\Livewire\Public\QuizRegister::class)
            ->set('name', 'Budi Santoso')
            ->set('position', 'Staf IT')
            ->set('email', 'budi.santoso@rsud.cb.go.id')
            ->set('phone', '081234567890')
            ->set('password', 'secretpassword')
            ->set('password_confirmation', 'secretpassword')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('successMessage', 'Pendaftaran Anda berhasil dikirim! Panitia akan melakukan verifikasi dan menyetujui pendaftaran Anda. Setelah disetujui, Anda dapat langsung login menggunakan password yang telah Anda masukkan.');

        $this->assertDatabaseHas('quiz_registrations', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_quiz_registration()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $registration = QuizRegistration::create([
            'name' => 'Budi Santoso',
            'position' => 'Staf IT',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'phone' => '081234567890',
            'password' => bcrypt('secretpassword'),
            'status' => 'pending',
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\QuizRegistrationManager::class)
            ->call('approve', $registration->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('quiz_registrations', [
            'id' => $registration->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@rsud.cb.go.id',
            'role' => 'participant',
            'is_active' => true,
        ]);
    }

    public function test_participant_can_answer_questions_and_finish_quiz()
    {
        $participant = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'participant',
            'is_active' => true,
        ]);

        QuizRegistration::create([
            'name' => 'Budi Santoso',
            'position' => 'Staf IT',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'phone' => '081234567890',
            'password' => bcrypt('secretpassword'),
            'status' => 'approved',
            'user_id' => $participant->id,
        ]);

        $question = QuizQuestion::create([
            'question' => 'Siapakah presiden pertama Indonesia?',
            'option_a' => 'Soeharto',
            'option_b' => 'Soekarno',
            'option_c' => 'B.J. Habibie',
            'option_d' => 'Gus Dur',
            'correct_answer' => 'b',
            'points' => 10,
            'status' => 'active',
        ]);

        Livewire::actingAs($participant)
            ->test(\App\Livewire\Participant\QuizDashboard::class)
            ->call('selectAnswer', $question->id, 'b')
            ->call('submitQuiz')
            ->assertHasNoErrors()
            ->assertSet('finished', true)
            ->assertSet('score', 10);

        $this->assertDatabaseHas('quiz_answers', [
            'user_id' => $participant->id,
            'quiz_question_id' => $question->id,
            'selected_answer' => 'b',
            'is_correct' => true,
        ]);

        $this->assertNotNull(QuizRegistration::where('user_id', $participant->id)->first()->finished_at);
    }

    public function test_admin_can_view_participant_details_and_toggle_active_status()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $participant = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'password' => bcrypt('password'),
            'role' => 'participant',
            'is_active' => true,
        ]);

        $reg = QuizRegistration::create([
            'name' => 'Budi Santoso',
            'position' => 'Staf IT',
            'email' => 'budi.santoso@rsud.cb.go.id',
            'phone' => '081234567890',
            'password' => bcrypt('secretpassword'),
            'status' => 'approved',
            'user_id' => $participant->id,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\QuizRegistrationManager::class)
            ->call('showDetails', $reg->id)
            ->assertSet('viewingRegDetails.id', $reg->id)
            ->call('toggleParticipantActive', $reg->id)
            ->assertHasNoErrors();

        // Should deactivate user
        $this->assertDatabaseHas('users', [
            'id' => $participant->id,
            'is_active' => false,
        ]);
    }
}
