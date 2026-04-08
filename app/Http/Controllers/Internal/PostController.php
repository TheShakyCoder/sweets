<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Post::class)) abort(403);
        
        return Inertia::render('Internal/Posts/Index', [
            'posts' => Post::orderBy('created_at', 'desc')->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Internal/Posts/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'description' => ['nullable', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('posts', 's3');
        }

        Post::create($validated);

        return to_route('internal.posts.index')
            ->with('success', '"' . $validated['title'] . '" published successfully.');
    }

    public function show(Post $post)
    {
        return Inertia::render('Internal/Posts/Show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('Internal/Posts/Edit', [
            'post' => array_merge($post->toArray(), [
                'thumbnail_url' => $post->thumbnail
                    ? \Illuminate\Support\Facades\Storage::disk('s3')->url($post->thumbnail)
                    : null,
            ]),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('posts', 'slug')->ignore($post->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'thumbnail'   => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($post->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('posts', 's3');
        }

        $post->update($validated);

        return to_route('internal.posts.index')
            ->with('success', '"' . $post->title . '" updated successfully.');
    }

    public function destroy(Post $post)
    {
        $title = $post->title;
        $post->delete();

        return to_route('internal.posts.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
