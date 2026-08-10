<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Quiz Sessions table ──────────────────────────────────────────
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_at')->nullable();          // waktu kuis dibuka
            $table->dateTime('end_at')->nullable();            // waktu kuis ditutup
            $table->unsignedInteger('duration_minutes')->default(60); // total durasi pengerjaan
            $table->unsignedInteger('time_per_question')->nullable(); // detik per soal (default semua soal)
            $table->string('status')->default('draft');        // draft | active | finished
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // ── 2. Link quiz_questions → quizzes ───────────────────────────────
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->foreignId('quiz_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('quizzes')
                  ->onDelete('set null');
            $table->unsignedInteger('time_limit')
                  ->nullable()
                  ->after('sort_order')
                  ->comment('Override detik per soal ini; null = pakai default kuis');
        });

        // ── 3. Link quiz_answers → quizzes ────────────────────────────────
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->foreignId('quiz_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('quizzes')
                  ->onDelete('set null');
        });

        // ── 4. Link quiz_registrations → quizzes ──────────────────────────
        Schema::table('quiz_registrations', function (Blueprint $table) {
            $table->foreignId('quiz_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('quizzes')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_registrations', fn(Blueprint $t) => $t->dropForeign(['quiz_id']));
        Schema::table('quiz_registrations', fn(Blueprint $t) => $t->dropColumn('quiz_id'));

        Schema::table('quiz_answers', fn(Blueprint $t) => $t->dropForeign(['quiz_id']));
        Schema::table('quiz_answers', fn(Blueprint $t) => $t->dropColumn('quiz_id'));

        Schema::table('quiz_questions', fn(Blueprint $t) => $t->dropForeign(['quiz_id']));
        Schema::table('quiz_questions', fn(Blueprint $t) => $t->dropColumns(['quiz_id', 'time_limit']));

        Schema::dropIfExists('quizzes');
    }
};
