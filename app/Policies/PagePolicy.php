<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    public function before(User $operator, string $ability): bool|null
    {
        if ($operator->is_admin) return true;
        return null;
    }

    public function viewAny(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.pages.index');
    }

    public function view(User $operator, Page $page): bool
    {
        return $operator->hasPermissionTo('internal.pages.show');
    }

    public function create(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.pages.store');
    }

    public function update(User $operator, Page $page): bool
    {
        return $operator->hasPermissionTo('internal.pages.update');
    }

    public function delete(User $operator, Page $page): bool
    {
        return $operator->hasPermissionTo('internal.pages.destroy');
    }

    public function restore(User $operator, Page $page): bool
    {
        return false;
    }

    public function forceDelete(User $operator, Page $page): bool
    {
        return false;
    }
}
