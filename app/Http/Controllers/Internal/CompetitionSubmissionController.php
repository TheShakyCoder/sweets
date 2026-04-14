<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionSubmission;

class CompetitionSubmissionController extends Controller
{
    public function destroy(Request $request, Competition $competition, CompetitionSubmission $submission)
    {
        if ($request->user()->cannot('delete', $submission)) abort(403);

        $submission->delete();

        return to_route('internal.competitions.show', $competition)
            ->with('success', 'Submission deleted.');
    }

    public function winner(Request $request, Competition $competition, CompetitionSubmission $submission)
    {
        if ($request->user()->cannot('declareWinner', $submission)) abort(403);

        // Clear any existing winner on this competition
        $competition->submissions()->update(['is_winner' => false]);

        $submission->update(['is_winner' => true]);
        $competition->update(['status' => 'results']);

        return to_route('internal.competitions.show', $competition)
            ->with('success', '"' . $submission->name . '" declared as winner. Competition status set to Results.');
    }
}
