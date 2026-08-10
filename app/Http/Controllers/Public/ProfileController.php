<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $slug): View|Response
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('public.profile', compact('page'));
    }
}
