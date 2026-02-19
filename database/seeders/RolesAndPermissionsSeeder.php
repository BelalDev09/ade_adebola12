<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'users.manage',
            'support.manage',
            'reviews.manage',
            'reports.manage',
            'cms.manage',
            'settings.smtp',
            'settings.account',
            'profile.view',
            'profile.edit',
            'roles.manage',
            'permissions.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);

        // Admin gets limited permissions
        Role::findByName('admin')->syncPermissions([
            'dashboard.view',
            'users.manage',
            'support.manage',
            'reviews.manage',
            'reports.manage',
            'cms.manage',
            'profile.view',
            'profile.edit',
        ]);
    }
}
