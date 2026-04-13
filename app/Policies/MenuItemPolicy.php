<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;

class MenuItemPolicy
{
    public function before(User $operator, string $ability): bool|null
    {
        if ($operator->is_admin) return true;
        return null;
    }

    public function viewAny(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.menu-items.index');
    }
    
    public function view(User $operator, MenuItem $menuItem): bool
    {
        return $operator->hasPermissionTo('internal.menu-items.show');
    }

    public function create(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.menu-items.store');
    }

    public function update(User $operator, MenuItem $menuItem): bool
    {
        return $operator->hasPermissionTo('internal.menu-items.update');
    }

    public function delete(User $operator, MenuItem $menuItem): bool
    {
        return $operator->hasPermissionTo('internal.menu-items.delete');
    }

    public function restore(User $operator, MenuItem $menuItem): bool
    {
        return false;
    }

    public function forceDelete(User $operator, MenuItem $menuItem): bool
    {
        return false;
    }
}
