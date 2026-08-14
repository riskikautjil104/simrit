<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\MediaPartner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = News::published()->with('category')->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('q')) {
            $query->where(fn($q) => $q->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('excerpt', 'like', '%' . $request->q . '%'));
        }

        $news       = $query->paginate(12)->withQueryString();
        $categories = Category::withCount(['news' => fn($q) => $q->published()])->get();

        return view('public.news.index', compact('news', 'categories'));
    }

    public function show(string $slug): View
    {
        $news    = News::published()->where('slug', $slug)->with('category', 'creator')->firstOrFail();
        News::whereKey($news->id)->increment('views');
        $news->refresh();
        $related = News::published()->where('id', '!=', $news->id)
            ->where('category_id', $news->category_id)
            ->latest('published_at')->limit(3)->get();
        $mediaPartners = MediaPartner::published()->get();
        return view('public.news.show', compact('news', 'related', 'mediaPartners'));
    }
}
