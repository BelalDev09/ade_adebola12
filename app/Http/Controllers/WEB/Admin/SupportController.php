<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SupportController extends Controller
{
    public function index()
    {
        return view('backend.admin.dynamic.support.index');
    }

    public function data()
    {
        $users = User::select(['id', 'first_name', 'last_name', 'email', 'avatar', 'description', 'created_at'])
            ->whereNotNull('description');
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('user', function ($row) {

                $avatarPath = $row->avatar;

                $avatar = ($row->avatar && Storage::disk('public')->exists($avatarPath))
                    ? asset('storage/' . $avatarPath)
                    : asset('storage/avatars/default.png');

                return '
    <div class="d-flex align-items-center gap-2">
        <img src="' . $avatar . '"
             width="40"
             height="40"
             style="border-radius:50%;object-fit:cover;">
        <div>
            <strong>' . $row->first_name . ' ' . $row->last_name . '</strong><br>
            <small>' . $row->email . '</small>
        </div>
    </div>
    ';
            })



            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route("admin.support.edit", $row->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                $del = '<form action="' . route("admin.support.destroy", $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field("DELETE") . '
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                                </form>';
                return $edit . ' ' . $del;
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }


    public function create()
    {
        return view('backend.admin.dynamic.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        Auth::user()->update([
            'description' => $request->description
        ]);

        return redirect()
            ->route('admin.support.index')
            ->with('success', 'Description added successfully');
    }


    public function edit(User $user)
    {
        // abort_if(Auth::id() !== $user->id, 403);
        return view('backend.admin.dynamic.support.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(Auth::id() !== $user->id, 403);

        $request->validate([
            'description' => 'required|string',
        ]);

        $user->update(['description' => $request->description]);

        return redirect()->route('admin.support.index')
            ->with('success', 'Description updated successfully');
    }

    public function destroy(User $user)
    {
        // abort_if(Auth::id() !== $user->id, 403);

        $user->update(['description' => null]);

        return redirect()->route('admin.support.index')
            ->with('success', 'Description deleted successfully');
    }
}
