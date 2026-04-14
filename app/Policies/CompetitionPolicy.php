<?php

namespace App\Policies;

use App\Models\Competition;
use App\Models\User;

class CompetitionPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->is_admin) return true;
        return null;
    }

    public function viewAny(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.competitions.index');
    }

    public function view(User $operator, Competition $competition): bool
    {
        return $operator->hasPermissionTo('internal.competitions.show');
    }

    public function create(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.competitions.store');
    }

    public function update(User $operator, Competition $competition): bool
    {
        return $operator->hasPermissionTo('internal.competitions.update');
    }

    public function delete(User $operator, Competition $competition): bool
    {
        return $operator->hasPermissionTo('internal.competitions.destroy');
    }

    public function restore(User $operator, Competition $competition): bool
    {
        return false;
    }

    public function forceDelete(User $operator, Competition $competition): bool
    {
        return false;
    }
}
