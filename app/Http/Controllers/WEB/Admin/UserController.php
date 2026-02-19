<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select(['id', 'first_name', 'last_name', 'email']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('roles', function($user){
                    return $user->roles->pluck('name')->map(fn($r)=>ucfirst($r))->toArray();
                })
                ->addColumn('permissions', function($user){
                    return $user->getAllPermissions()->pluck('name')->map(fn($p)=>ucfirst($p))->toArray();
                })
                ->addColumn('action', function($user){
                    $rolesJson = htmlspecialchars($user->roles->pluck('id')->toJson(), ENT_QUOTES, 'UTF-8');
                    return '
                        <button class="btn btn-sm btn-warning me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#userModal"
                            data-action="edit"
                            data-id="'.$user->id.'"
                            data-first_name="'.$user->first_name.'"
                            data-last_name="'.$user->last_name.'"
                            data-email="'.$user->email.'"
                            data-roles=\''.$rolesJson.'\'>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-user" data-id="'.$user->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = Role::orderBy('name')->get();
        return view('backend.admin.user.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'=>'nullable|string|max:255',
            'last_name'=>'nullable|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'roles'=>'nullable|array',
            'roles.*'=>'integer|exists:roles,id'
        ]);

        $data = $request->only('first_name','last_name','email');
        $data['name'] = trim(($request->first_name ?? '').' '.($request->last_name ?? ''));
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->syncRoles($request->roles ?? []);

        return response()->json(['success'=>'User added successfully']);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name'=>'nullable|string|max:255',
            'last_name'=>'nullable|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'nullable|string|min:6',
            'roles'=>'nullable|array',
            'roles.*'=>'integer|exists:roles,id'
        ]);

        $data = $request->only('first_name','last_name','email');
        $data['name'] = trim(($request->first_name ?? '').' '.($request->last_name ?? ''));

        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles ?? []);

        return response()->json(['success'=>'User updated successfully']);
    }

    public function destroy(User $user){
        $user->delete();
        return response()->json(['success'=>'User deleted successfully']);
    }
}
