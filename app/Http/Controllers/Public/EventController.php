<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::published()->latest('starts_at');
        if ($request->filled('q')) {
            $query->where(fn($q) => $q->where('title','like','%'.$request->q.'%')
                ->orWhere('location','like','%'.$request->q.'%'));
        }
        $events = $query->paginate(12)->withQueryString();
        return view('public.events.index', compact('events'));
    }

    public function show(string $slug): View
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        return view('public.events.show', compact('event'));
    }
}
