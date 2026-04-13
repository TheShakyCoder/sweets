<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $page->load('thumbnail');

        return Inertia::render('Page/Show', [
            'page'        => array_merge($page->toArray(), [
                'thumbnail_url' => $page->thumbnail?->url,
            ]),
            'canLogin'    => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
