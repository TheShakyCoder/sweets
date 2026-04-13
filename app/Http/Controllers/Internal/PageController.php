<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Page;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Page::class)) abort(403);

        return Inertia::render('Internal/Pages/Index', [
            'pages' => Page::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Page::class)) abort(403);

        return Inertia::render('Internal/Pages/Create');
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Page::class)) abort(403);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'description'  => ['nullable', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
        ]);

        Page::create($validated);

        return to_route('internal.pages.index')
            ->with('success', '"' . $validated['title'] . '" created successfully.');
    }

    public function edit(Request $request, Page $page)
    {
        if ($request->user()->cannot('update', $page)) abort(403);

        return Inertia::render('Internal/Pages/Edit', [
            'page' => array_merge($page->toArray(), [
                'thumbnail_url' => $page->thumbnail?->url,
            ]),
        ]);
    }

    public function update(Request $request, Page $page)
    {
        if ($request->user()->cannot('update', $page)) abort(403);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page->id)],
            'description'  => ['nullable', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'thumbnail_id' => ['nullable', 'uuid', 'exists:media,id'],
        ]);

        $page->update($validated);

        return to_route('internal.pages.index')
            ->with('success', '"' . $page->title . '" updated successfully.');
    }

    public function destroy(Request $request, Page $page)
    {
        if ($request->user()->cannot('delete', $page)) abort(403);

        $title = $page->title;
        $page->delete();

        return to_route('internal.pages.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
