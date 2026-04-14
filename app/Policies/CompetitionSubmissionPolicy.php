<?php

namespace App\Policies;

use App\Models\Competition;
use App\Models\CompetitionSubmission;
use App\Models\User;

class CompetitionSubmissionPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) return true;
        return null;
    }

    // Any authenticated user can submit to an open competition
    public function create(User $operator, Competition $competition): bool
    {
        return $competition->status === 'open';
    }

    // Editors can delete any submission
    public function delete(User $operator, CompetitionSubmission $submission): bool
    {
        return $operator->hasPermissionTo('internal.competitions.show');
    }

    // Editors can declare a winner
    public function declareWinner(User $operator, CompetitionSubmission $submission): bool
    {
        return $operator->hasPermissionTo('internal.competitions.show');
    }
}
