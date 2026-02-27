<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
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
        return $user->can('role-list');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('role-list');
    }

    public function create(User $user): bool
    {
        return $user->can('role-create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('role-edit');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('role-delete');
    }
}
