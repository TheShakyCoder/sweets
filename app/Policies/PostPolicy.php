<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $operator, string $ability): bool|null
    {
        if ($operator->is_admin) return true;
        return null;
    }

    public function viewAny(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.posts.index');
    }

    public function view(User $operator, Post $post): bool
    {
        return $operator->hasPermissionTo('internal.posts.show');
    }

    public function create(User $operator): bool
    {
        return $operator->hasPermissionTo('internal.posts.store');
    }

    public function update(User $operator, Post $post): bool
    {
        return $operator->hasPermissionTo('internal.posts.update');
    }

    public function delete(User $operator, Post $post): bool
    {
        return $operator->hasPermissionTo('internal.posts.destroy');
    }

    public function restore(User $operator, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $operator, Post $post): bool
    {
        return false;
    }
}
