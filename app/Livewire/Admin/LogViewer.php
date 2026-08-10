<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class LogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = '';

    public function updatingSearch()      { $this->resetPage(); }
    public function updatingActionFilter() { $this->resetPage(); }

    public function render()
    {
        $query = ActivityLog::with('user')->latest();

        if ($this->search) {
            $query->where('description', 'like', '%'.$this->search.'%');
        }
        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        $logs    = $query->paginate(20);
        $actions = ActivityLog::distinct()->pluck('action');

        return view('livewire.admin.log-viewer', compact('logs', 'actions'))
            ->layout('layouts.admin', ['title' => 'Log Aktivitas Sistem']);
    }
}
