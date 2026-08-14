<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MediaPartner;
use Illuminate\View\View;

class MediaPartnerController extends Controller
{
    public function show(string $slug): View
    {
        $partner = MediaPartner::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $otherPartners = MediaPartner::published()
            ->whereKeyNot($partner->id)
            ->limit(6)
            ->get();

        return view('public.media-partners.show', compact('partner', 'otherPartners'));
    }
}
