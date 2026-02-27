<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
        return $user->can('user-list');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('user-list');
    }

    public function create(User $user): bool
    {
        return $user->can('user-create');
    }

    public function update(User $user, User $model): bool
    {

        return $user->can('user-edit') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {

        if ($user->id === $model->id) {
            return false;
        }

        return $user->can('user-delete');
    }
}
