<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Video::published()->latest('published_at')->paginate(12);
        return view('public.videos.index', compact('videos'));
    }
}
