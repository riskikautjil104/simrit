<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $categories = DocumentCategory::withCount(['documents' => fn($q) => $q->published()])->get();

        $query = Document::published()->with('category')->latest('published_at');
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('q')) {
            $query->where('title','like','%'.$request->q.'%');
        }

        $documents = $query->paginate(15)->withQueryString();
        return view('public.documents.index', compact('documents', 'categories'));
    }

    public function download(int $id): StreamedResponse
    {
        $document = Document::published()->findOrFail($id);
        return Storage::disk('public')->download($document->file_path, $document->original_filename);
    }
}
