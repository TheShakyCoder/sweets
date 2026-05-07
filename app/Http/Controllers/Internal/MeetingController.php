<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $meetings = Meeting::with('activity')
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->activity_id, fn ($q, $id) => $q->where('activity_id', $id))
            ->orderBy('starts_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Internal/Meetings/Index', [
            'meetings'   => $meetings,
            'activities' => Activity::orderBy('title')->get(['id', 'title']),
            'filters'    => $request->only(['search', 'activity_id']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Internal/Meetings/Create', [
            'activities'  => Activity::where('status', 'active')->orderBy('title')->get(['id', 'title']),
            'activity_id' => $request->activity_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'activity_id'        => ['nullable', 'uuid', 'exists:activities,id'],
            'starts_at'          => ['required', 'date'],
            'ends_at'            => ['nullable', 'date', 'after:starts_at'],
            'location'           => ['nullable', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'recurrence'         => ['nullable', 'string', Rule::in(['weekly', 'fortnightly', 'monthly'])],
            'recurrence_ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        Meeting::create($validated);

        return to_route('internal.meetings.index')
            ->with('success', '"' . $validated['title'] . '" created successfully.');
    }

    public function edit(Meeting $meeting)
    {
        return Inertia::render('Internal/Meetings/Edit', [
            'meeting'    => $meeting,
            'activities' => Activity::where('status', 'active')->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'activity_id'        => ['nullable', 'uuid', 'exists:activities,id'],
            'starts_at'          => ['required', 'date'],
            'ends_at'            => ['nullable', 'date', 'after:starts_at'],
            'location'           => ['nullable', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'recurrence'         => ['nullable', 'string', Rule::in(['weekly', 'fortnightly', 'monthly'])],
            'recurrence_ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        $meeting->update($validated);

        return to_route('internal.meetings.index')
            ->with('success', '"' . $meeting->title . '" updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $title = $meeting->title;
        $meeting->delete();

        return to_route('internal.meetings.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
