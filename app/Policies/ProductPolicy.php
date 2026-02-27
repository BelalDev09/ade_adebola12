<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
        return $user->can('product-list');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('product-list');
    }

    public function create(User $user): bool
    {
        return $user->can('product-create');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('product-edit');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('product-delete');
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->can('product-restore');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->can('product-force-delete');
    }
}
