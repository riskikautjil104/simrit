<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MediaPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'link',
        'sort_order',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function booted(): void
    {
        static::saving(function (MediaPartner $partner) {
            if (! $partner->slug || ($partner->isDirty('name') && ! $partner->isDirty('slug'))) {
                $partner->slug = static::uniqueSlug($partner->name, $partner->id);
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'media-partner';
        $slug = $base;
        $suffix = 1;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$suffix);
        }

        return $slug;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->orderBy('sort_order');
    }
}
