<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageViewController extends Controller
{
    public function index(Request $request)
    {
        $query = PageView::latest('viewed_at')
            ->when($request->search, function ($q, $s) {
                $q->where('url', 'like', "%{$s}%")
                  ->orWhere('ip', 'like', "%{$s}%")
                  ->orWhere('user_agent', 'like', "%{$s}%");
            })
            ->when($request->date_from, fn ($q, $d) => $q->where('viewed_at', '>=', $d))
            ->when($request->date_to, fn ($q, $d) => $q->where('viewed_at', '<=', $d . ' 23:59:59'));

        $pageViews = $query->paginate(50)->withQueryString();

        // Summary stats for the header
        $stats = [
            'today'      => PageView::whereDate('viewed_at', today())->count(),
            'this_week'  => PageView::where('viewed_at', '>=', now()->startOfWeek())->count(),
            'this_month' => PageView::where('viewed_at', '>=', now()->startOfMonth())->count(),
            'total'      => PageView::count(),
        ];

        // Top pages
        $topPages = PageView::selectRaw('url, COUNT(*) as views')
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('url')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        return Inertia::render('Internal/PageViews/Index', [
            'pageViews' => $pageViews,
            'stats'     => $stats,
            'topPages'  => $topPages,
            'filters'   => $request->only(['search', 'date_from', 'date_to']),
        ]);
    }
}
