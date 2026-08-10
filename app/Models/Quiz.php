<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_at',
        'end_at',
        'duration_minutes',
        'time_per_question',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_at'  => 'datetime',
        'end_at'    => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(QuizRegistration::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'draft')
                     ->whereNotNull('start_at')
                     ->where('start_at', '>', now());
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /** True jika kuis sedang dalam window waktu mulai–selesai */
    public function isOpen(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        $now = now();
        if ($this->start_at && $now->lt($this->start_at)) {
            return false;   // belum mulai
        }
        if ($this->end_at && $now->gt($this->end_at)) {
            return false;   // sudah selesai
        }
        return true;
    }

    /** Detik tersisa sampai kuis dimulai (0 jika sudah mulai) */
    public function secondsUntilStart(): int
    {
        if (! $this->start_at || now()->gte($this->start_at)) {
            return 0;
        }
        return (int) now()->diffInSeconds($this->start_at);
    }

    /** Detik tersisa sampai kuis berakhir (null jika tidak ada end_at) */
    public function secondsUntilEnd(): ?int
    {
        if (! $this->end_at) {
            return null;
        }
        if (now()->gt($this->end_at)) {
            return 0;
        }
        return (int) now()->diffInSeconds($this->end_at);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'Draft',
            'active'   => 'Aktif',
            'finished' => 'Selesai',
            default    => $this->status,
        };
    }
}
