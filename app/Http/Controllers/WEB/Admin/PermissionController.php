<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::withCount('roles')->orderBy('name');

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('roles', function ($permission) {
                    return $permission->roles_count;
                })
                ->addColumn('action', function ($permission) {
                    return '
                        <button class="btn btn-sm btn-warning me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#permissionModal"
                            data-action="edit"
                            data-id="' . $permission->id . '"
                            data-name="' . $permission->name . '">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-permission" data-id="' . $permission->id . '">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.admin.permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => 'Permission created successfully']);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('permissions', 'name')->ignore($permission->id)],
        ]);

        $permission->update(['name' => $request->name]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => 'Permission updated successfully']);
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return response()->json(['message' => 'Permission is assigned to roles and cannot be deleted.'], 422);
        }

        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json(['success' => 'Permission deleted successfully']);
    }
}