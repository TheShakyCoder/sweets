<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Media::class)) abort(403);

        $media = Media::latest()
            ->when($request->search, fn($q, $s) => $q->where('filename', 'like', "%{$s}%"))
            ->paginate(24);

        if ($request->wantsJson()) {
            return response()->json(['media' => $media]);
        }

        return Inertia::render('Internal/Media/Index', [
            'media'  => $media,
            'search' => $request->search ?? '',
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Attempting to upload media file: ' . $request->file('files')[0]->getClientOriginalName());

        $request->validate([
            'files'   => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf', 'max:10240'],
        ]);

        Log::info('Validation passed for media upload. Processing files...');

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs(
                    'media',
                    Str::uuid() . '.' . $file->getClientOriginalExtension(),
                    ['disk' => 's3', 'throw' => true],
                );

                if (!$path) {
                    Log::error('Failed to upload file to storage (no path generated): ' . $originalName);
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
            } catch (\Exception $e) {
                Log::error('Media upload failed for file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
                return response()->json(['message' => 'Failed to upload ' . $file->getClientOriginalName() . ': ' . $e->getMessage()], 500);
            }
        }

        Log::info('Media upload successful for ' . count($uploaded) . ' file(s).');

        return response()->json([
            'media' => collect($uploaded)->map(fn($m) => [
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
