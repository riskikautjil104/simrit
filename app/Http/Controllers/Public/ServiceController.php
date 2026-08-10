<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::published()->get();
        return view('public.services.index', compact('services'));
    }

    public function show(string $slug): View
    {
        $service = Service::published()->where('slug', $slug)->firstOrFail();
        return view('public.services.show', compact('service'));
    }
}
