<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionSubmission;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::whereIn('status', ['open', 'closed', 'results'])
            ->with('thumbnail')
            ->latest()
            ->get()
            ->map(fn ($c) => [
                'id'            => $c->id,
                'title'         => $c->title,
                'slug'          => $c->slug,
                'description'   => $c->description,
                'status'        => $c->status,
                'thumbnail_url' => $c->thumbnail?->url,
                'created_at'    => $c->created_at->format('j M Y'),
            ]);

        return Inertia::render('Competition/Index', [
            'competitions' => $competitions,
            'canLogin'     => Route::has('login'),
            'canRegister'  => Route::has('register'),
        ]);
    }

    public function show(string $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();
        $competition->load('thumbnail');

        $submissions = $competition->submissions()
            ->with('media')
            ->orderByDesc('is_winner')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'description' => $s->description,
                'is_winner'   => $s->is_winner,
                'image_url'   => $s->media?->url,
            ]);

        $userSubmission = null;
        if (auth()->check()) {
            $sub = $competition->submissions()
                ->with('media')
                ->where('user_id', auth()->id())
                ->first();

            if ($sub) {
                $userSubmission = [
                    'name'        => $sub->name,
                    'description' => $sub->description,
                    'image_url'   => $sub->media?->url,
                    'is_winner'   => $sub->is_winner,
                ];
            }
        }

        return Inertia::render('Competition/Show', [
            'competition'    => array_merge($competition->toArray(), [
                'thumbnail_url' => $competition->thumbnail?->url,
            ]),
            'submissions'    => $submissions,
            'userSubmission' => $userSubmission,
            'canLogin'       => Route::has('login'),
            'canRegister'    => Route::has('register'),
        ]);
    }

    public function submit(Request $request, string $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        if ($request->user()->cannot('create', [CompetitionSubmission::class, $competition])) {
            abort(403, 'This competition is not currently accepting submissions.');
        }

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'image'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $mediaId = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storeAs(
                'competitions/' . $competition->id,
                Str::uuid() . '.' . $file->getClientOriginalExtension(),
                's3'
            );

            $media = Media::create([
                'filename'    => $file->getClientOriginalName(),
                'path'        => $path,
                'disk'        => 's3',
                'mime_type'   => $file->getMimeType(),
                'size'        => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);

            $mediaId = $media->id;
        }

        CompetitionSubmission::create([
            'competition_id' => $competition->id,
            'user_id'        => $request->user()->id,
            'name'           => $request->name,
            'description'    => $request->description,
            'media_id'       => $mediaId,
        ]);

        return to_route('competitions.show', $slug)
            ->with('success', 'Your entry has been submitted. Good luck!');
    }
}
