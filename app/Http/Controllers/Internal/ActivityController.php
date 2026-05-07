<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with('thumbnail')
            ->withCount('meetings')
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Internal/Activities/Index', [
            'activities' => $activities,
            'search'     => $request->search ?? '',
        ]);
    }

    public function create()
    {
        return Inertia::render('Internal/Activities/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', 'unique:activities,slug'],
            'description'  => ['nullable', 'string', 'max:500'],
            'content'      => ['nullable', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
            'status'       => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        Activity::create($validated);

        return to_route('internal.activities.index')
            ->with('success', '"' . $validated['title'] . '" created successfully.');
    }

    public function show(Activity $activity)
    {
        $activity->load(['thumbnail', 'meetings' => fn ($q) => $q->orderBy('starts_at')]);

        return Inertia::render('Internal/Activities/Show', [
            'activity' => $activity,
        ]);
    }

    public function edit(Activity $activity)
    {
        return Inertia::render('Internal/Activities/Edit', [
            'activity' => array_merge($activity->toArray(), [
                'thumbnail_url' => $activity->thumbnail?->url,
            ]),
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', Rule::unique('activities', 'slug')->ignore($activity->id)],
            'description'  => ['nullable', 'string', 'max:500'],
            'content'      => ['nullable', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
            'status'       => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        $activity->update($validated);

        return to_route('internal.activities.index')
            ->with('success', '"' . $activity->title . '" updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $title = $activity->title;
        $activity->delete();

        return to_route('internal.activities.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
