<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    /**
     * Public calendar page showing all meetings for a given month.
     */
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to   = $from->copy()->endOfMonth();

        $meetings = Meeting::with('activity')
            ->where(function ($q) use ($from, $to) {
                // Non-recurring in range
                $q->whereNull('recurrence')
                  ->whereBetween('starts_at', [$from, $to]);
            })
            ->orWhere(function ($q) use ($from) {
                // Recurring that started on or before end of range
                $q->whereNotNull('recurrence')
                  ->where('starts_at', '<=', $from->copy()->endOfMonth())
                  ->where(function ($q2) use ($from) {
                      $q2->whereNull('recurrence_ends_at')
                         ->orWhere('recurrence_ends_at', '>=', $from);
                  });
            })
            ->get();

        $occurrences = $meetings
            ->flatMap(fn ($m) => $m->occurrences($from, $to))
            ->sortBy('starts_at')
            ->values()
            ->all();

        $activities = Activity::where('status', 'active')
            ->withCount(['meetings' => fn ($q) => $q->where('starts_at', '>=', now())])
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'description']);

        return Inertia::render('Calendar/Index', [
            'occurrences' => $occurrences,
            'activities'  => $activities,
            'month'       => $month,
            'year'        => $year,
        ]);
    }
}
