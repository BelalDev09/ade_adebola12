<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // Index + DataTable
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'first_name', 'last_name', 'email', 'avatar', 'created_at']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', fn ($row) => $row->name)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#userModal"
                            data-action="edit"
                            data-id="'.$row->id.'"
                            data-first_name="'.$row->first_name.'"
                            data-last_name="'.$row->last_name.'"
                            data-email="'.$row->email.'"
                            data-avatar="'.($row->avatar ? asset("storage/".$row->avatar) : '').'">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-user" data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.admin.user.index');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only('first_name', 'last_name', 'email');
        $data['name'] = $request->first_name.' '.$request->last_name;
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return response()->json(['success' => 'User added successfully']);
    }

    // Update
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'password'   => 'nullable|string|min:6',
            'avatar'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only('first_name', 'last_name', 'email');
        $data['name'] = $request->first_name.' '.$request->last_name;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json(['success' => 'User updated successfully']);
    }

    // Delete
    public function destroy(User $user)
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return response()->json(['success' => 'User deleted successfully']);
    }
}
