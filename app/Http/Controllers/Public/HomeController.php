<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\MediaPartner;
use App\Models\News;
use App\Models\Page;
use App\Models\Portal;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $latestNews     = News::published()->with('category')->latest('published_at')->limit(6)->get();
        $latestEvents   = Event::published()->latest('starts_at')->limit(4)->get();
        $announcements  = Announcement::published()->latest('published_at')->limit(3)->get();
        $portals        = Portal::published()->get();
        $services       = Service::published()->get();
        $visionMission  = Page::where('page_key', 'vision-mission')->where('status', 'published')->first();
        $mediaPartners  = MediaPartner::published()->get();

        return view('public.home', compact('latestNews', 'latestEvents', 'announcements', 'portals', 'services', 'visionMission', 'mediaPartners'));
    }
}
