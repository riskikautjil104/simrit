<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    /**
     * Handle inline image uploads coming from the Tiptap rich-text editor
     * (used by News, Events, Announcements, Pages, etc.).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Max 5MB
        ]);

        $file = $request->file('image');
        $filename = 'editor/' . date('Y/m') . '/' . Str::random(24) . '.' . $file->getClientOriginalExtension();

        $file->storeAs('', $filename, 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($filename),
        ]);
    }
}
