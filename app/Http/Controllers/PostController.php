<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('thumbnail')->latest()
            ->paginate(9)
            ->through(fn ($post) => [
                'id'            => $post->id,
                'title'         => $post->title,
                'slug'          => $post->slug,
                'excerpt'       => str($post->content)->stripTags()->limit(160)->toString(),
                'created_at'    => $post->created_at->format('j M Y'),
                'thumbnail_url' => $post->thumbnail?->url,
            ]);

        return Inertia::render('Post/Index', [
            'posts'       => $posts,
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function show(Post $post)
    {
        $post->load('thumbnail');

        return Inertia::render('Post/Show', [
            'post'        => array_merge($post->toArray(), [
                'thumbnail_url' => $post->thumbnail?->url,
            ]),
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
