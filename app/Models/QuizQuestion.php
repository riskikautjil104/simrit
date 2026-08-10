<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'image_path',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'sort_order',
        'time_limit',
        'status',
        'created_by',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Effective time limit in seconds.
     * Uses this question's own time_limit, or falls back to the parent quiz default.
     * Returns null if neither is set (unlimited).
     */
    public function getEffectiveTimeLimitAttribute(): ?int
    {
        if ($this->time_limit !== null) {
            return (int) $this->time_limit;
        }
        return $this->quiz?->time_per_question;
    }
}
