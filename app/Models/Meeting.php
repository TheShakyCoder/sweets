<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Concerns\TracksFieldChanges;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

#[Fillable(['title', 'activity_id', 'starts_at', 'ends_at', 'location', 'description', 'recurrence', 'recurrence_ends_at'])]
class Meeting extends Model
{
    use SoftDeletes, HasUuids, TracksFieldChanges;

    protected function casts(): array
    {
        return [
            'starts_at'          => 'datetime',
            'ends_at'            => 'datetime',
            'recurrence_ends_at' => 'date',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Expand a single meeting row into all its occurrences between $from and $to.
     * Non-recurring meetings return themselves if they fall within the range.
     */
    public function occurrences(Carbon $from, Carbon $to): array
    {
        if (! $this->recurrence) {
            if ($this->starts_at->between($from, $to)) {
                return [$this->toOccurrence($this->starts_at, $this->ends_at)];
            }
            return [];
        }

        $interval = match ($this->recurrence) {
            'weekly'      => '1 week',
            'fortnightly' => '2 weeks',
            'monthly'     => '1 month',
            default       => null,
        };

        if (! $interval) {
            return [];
        }

        $end = $this->recurrence_ends_at
            ? Carbon::parse($this->recurrence_ends_at)->endOfDay()->min($to)
            : $to;

        $duration = $this->ends_at ? $this->starts_at->diffInMinutes($this->ends_at) : null;

        $occurrences = [];
        $period = CarbonPeriod::create($this->starts_at, $interval, $end);

        foreach ($period as $date) {
            if ($date->lt($from)) continue;
            if ($date->gt($to)) break;

            $occEnd = $duration ? $date->copy()->addMinutes($duration) : null;
            $occurrences[] = $this->toOccurrence($date, $occEnd);
        }

        return $occurrences;
    }

    protected function toOccurrence(Carbon $start, ?Carbon $end): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'starts_at'   => $start->toIso8601String(),
            'ends_at'     => $end?->toIso8601String(),
            'location'    => $this->location,
            'description' => $this->description,
            'recurrence'  => $this->recurrence,
            'activity'    => $this->activity ? [
                'id'    => $this->activity->id,
                'title' => $this->activity->title,
                'slug'  => $this->activity->slug,
            ] : null,
        ];
    }
}
