<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleries = Gallery::published()->latest('published_at')->paginate(12);
        return view('public.galleries.index', compact('galleries'));
    }

    public function show(string $slug): View
    {
        $gallery = Gallery::published()->where('slug', $slug)->with('items')->firstOrFail();
        return view('public.galleries.show', compact('gallery'));
    }
}
