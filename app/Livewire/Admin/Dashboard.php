<?php

namespace App\Livewire\Admin;

use App\Models\News;
use App\Models\Event;
use App\Models\Document;
use App\Models\TeamMember;
use App\Models\ActivityLog;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'news'      => News::count(),
            'events'    => Event::count(),
            'documents' => Document::count(),
            'team'      => TeamMember::count(),
        ];

        $recentLogs = ActivityLog::with('user')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentLogs'))
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
