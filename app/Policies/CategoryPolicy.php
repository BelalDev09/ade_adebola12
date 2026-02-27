<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Super Admin
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->can('category-list');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->can('category-list');
    }

    public function create(User $user): bool
    {
        return $user->can('category-create');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->can('category-edit');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can('category-delete');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->can('category-restore');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->can('category-force-delete');
    }
}
