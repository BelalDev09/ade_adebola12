<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['roles'])->orderBy('email');

            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles
                        ->map(fn($role) => [
                            'id' => $role->id,
                            'name' => $role->name,
                        ])
                        ->values()
                        ->all();
                })
                ->addColumn('permissions', function ($user) {
                    return $user->getAllPermissions()->pluck('name')->toArray();
                })
                ->addColumn('email', fn($user) => $user->email)
                ->addColumn('id', fn($user) => $user->id)
                ->addColumn('first_role_id', fn($user) => optional($user->roles->first())->id)
                ->make(true);
        }

        return view('backend.admin.roles.index');
    }

    public function create()
    {
        return view('backend.admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        return view('backend.admin.roles.create', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('roles', 'name')->ignore($role->id)],
        ]);

        $role->update(['name' => $request->name]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function permissions(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $permissionGroups = $permissions->groupBy(function ($permission) {
            return str_contains($permission->name, '.')
                ? explode('.', $permission->name, 2)[0]
                : 'general';
        });
        $assigned = $role->permissions->pluck('id')->all();

        return view('backend.admin.roles.add_permissions', compact('role', 'permissionGroups', 'assigned'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->syncPermissions($request->input('permissions', []));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.permissions', $role)
            ->with('success', 'Permissions updated successfully');
    }

    public function destroy(Request $request, Role $role)
    {
        if ($role->name === 'superadmin') {
            return response()->json(['message' => 'Superadmin role cannot be deleted.'], 422);
        }

        if ($role->users()->count() > 0) {
            return response()->json(['message' => 'Role is assigned to users. Remove it from users first or use force delete.'], 422);
        }

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => 'Role deleted successfully']);
    }

    public function forceDestroy(Role $role)
    {
        if ($role->name === 'superadmin') {
            return response()->json(['message' => 'Superadmin role cannot be deleted.'], 422);
        }

        $role->users()->detach();
        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => 'Role removed from users and deleted successfully']);
    }

    public function assignUserRole(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::where('email', $request->string('email'))->firstOrFail();
        $user->syncRoles([$request->string('role')->toString()]);

        return response()->json(['success' => 'Role assigned successfully']);
    }

    public function removeUserRole(User $user, Role $role)
    {
        if (! $user->hasRole($role->name)) {
            return response()->json(['message' => 'User does not have this role.'], 422);
        }

        $user->removeRole($role->name);

        return response()->json(['success' => 'Role removed successfully']);
    }
}
