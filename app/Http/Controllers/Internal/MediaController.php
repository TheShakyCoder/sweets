<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::latest()
            ->when($request->search, fn ($q, $s) => $q->where('filename', 'like', "%{$s}%"))
            ->paginate(24);

        if ($request->wantsJson()) {
            return response()->json(['media' => $media]);
        }

        return Inertia::render('Admin/Media/Index', [
            'media'  => $media,
            'search' => $request->search ?? '',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files'   => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf', 'max:10240'],
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs(
                'media/' . now()->format('Y/m'),
                Str::uuid() . '.' . $file->getClientOriginalExtension(),
                's3'
            );

            if (!$path) {
                return response()->json(['message' => 'Failed to upload ' . $originalName . ' to storage.'], 500);
            }

            $media = Media::create([
                'filename'    => $originalName,
                'path'        => $path,
                'disk'        => 's3',
                'mime_type'   => $file->getMimeType(),
                'size'        => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);

            $uploaded[] = $media->fresh();
        }

        return response()->json([
            'media' => collect($uploaded)->map(fn ($m) => [
                'id'        => $m->id,
                'filename'  => $m->filename,
                'url'       => $m->url,
                'mime_type' => $m->mime_type,
                'size'      => $m->size,
            ]),
        ]);
    }

    public function update(Request $request, Media $medium)
    {
        $request->validate(['alt' => ['nullable', 'string', 'max:255']]);
        $medium->update(['alt' => $request->alt]);
        return response()->json(['media' => $medium->fresh()]);
    }

    public function destroy(Media $medium)
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('success', '"' . $medium->filename . '" deleted.');
    }
}
