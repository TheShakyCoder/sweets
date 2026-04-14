<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Competition;

class CompetitionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Competition::class)) abort(403);

        return Inertia::render('Internal/Competitions/Index', [
            'competitions' => Competition::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Competition::class)) abort(403);

        return Inertia::render('Internal/Competitions/Create');
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Competition::class)) abort(403);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', 'unique:competitions,slug'],
            'description'  => ['nullable', 'string', 'max:255'],
            'content'      => ['nullable', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
            'status'       => ['required', Rule::in(['open', 'closed', 'results'])],
        ]);

        Competition::create($validated);

        return to_route('internal.competitions.index')
            ->with('success', '"' . $validated['title'] . '" created successfully.');
    }

    public function show(Request $request, Competition $competition)
    {
        if ($request->user()->cannot('view', $competition)) abort(403);

        $competition->load('thumbnail');

        $submissions = $competition->submissions()
            ->with(['user', 'media'])
            ->orderByDesc('is_winner')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'description' => $s->description,
                'is_winner'   => $s->is_winner,
                'created_at'  => $s->created_at->format('j M Y'),
                'user'        => ['name' => $s->user->name],
                'image_url'   => $s->media?->url,
            ]);

        return Inertia::render('Internal/Competitions/Show', [
            'competition' => array_merge($competition->toArray(), [
                'thumbnail_url'     => $competition->thumbnail?->url,
                'submissions_count' => $submissions->count(),
            ]),
            'submissions' => $submissions,
        ]);
    }

    public function edit(Request $request, Competition $competition)
    {
        if ($request->user()->cannot('update', $competition)) abort(403);

        return Inertia::render('Internal/Competitions/Edit', [
            'competition' => array_merge($competition->toArray(), [
                'thumbnail_url' => $competition->thumbnail?->url,
            ]),
        ]);
    }

    public function update(Request $request, Competition $competition)
    {
        if ($request->user()->cannot('update', $competition)) abort(403);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', Rule::unique('competitions', 'slug')->ignore($competition->id)],
            'description'  => ['nullable', 'string', 'max:255'],
            'content'      => ['nullable', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
            'status'       => ['required', Rule::in(['open', 'closed', 'results'])],
        ]);

        $competition->update($validated);

        return to_route('internal.competitions.index')
            ->with('success', '"' . $competition->title . '" updated successfully.');
    }

    public function destroy(Request $request, Competition $competition)
    {
        if ($request->user()->cannot('delete', $competition)) abort(403);

        $title = $competition->title;
        $competition->delete();

        return to_route('internal.competitions.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
