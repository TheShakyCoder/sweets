<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Route;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\Post;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if($request->user()) {
            $can = $request->user()->getPermissions();
        }

        // Load menu items from database
        $navLinks = MenuItem::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->label,
                'href' => $item->href,
                'children' => $item->children->map(fn ($child) => [
                    'label' => $child->label,
                    'href' => $child->href,
                ])->values()->toArray(),
            ])
            ->toArray();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
            'site' => [
                'fullname' => config('site.fullname'),
                'email' => config('site.email'),
                'telephone' => config('site.telephone'),
                'address' => config('site.address'),
                'opening_times' => config('site.opening_times'),
                'established' => config('site.established'),
                'social' => config('site.social'),
                'nav_links' => $navLinks,
            ],

            'can' => $can ?? [],
        ];
    }
}
