<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        ?object $subject = null,
        array $metadata = []
    ): void {
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id ?? null,
            'description'  => $description,
            'metadata'     => empty($metadata) ? null : $metadata,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
            'created_at'   => now(),
        ]);
    }
}
