<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller
{
    /**
     * Show the user's profile (view-only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        return view('backend.pages.profile', [
            'user' => $request->user()
        ]);
    }

    /**
     * Show the user's profile edit form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:100',
            'country'     => 'nullable|string|max:100',
            'zip_code'    => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:150',
            'website'     => 'nullable|url',
            'description' => 'nullable|string',
            'skills'      => 'nullable|string',
            'avatar'      => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
            'avatar_remove' => 'nullable|in:0,1',
            'cover_image_remove' => 'nullable|in:0,1',
        ]);

        // Fill profile base fields
        $user->first_name  = $validated['first_name'];
        $user->last_name   = $validated['last_name'];
        $user->email       = $validated['email'];
        $user->phone       = $validated['phone'] ?? null;
        $user->city        = $validated['city'] ?? null;
        $user->country     = $validated['country'] ?? null;
        $user->zip_code    = $validated['zip_code'] ?? null;
        $user->designation = $validated['designation'] ?? null;
        $user->website     = $validated['website'] ?? null;
        $user->description = $validated['description'] ?? null;
        $user->name        = $validated['first_name'] . ' ' . $validated['last_name'];

        // Skills: convert textarea to array and store as JSON if needed
        if (!empty($validated['skills'])) {
            $skills = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|,/", $validated['skills']))));
            // Check if the user model casts 'skills' to array/json, else serialize
            $user->skills = $skills;
        } else {
            $user->skills = null;
        }

        // Handle avatar upload
        if (($validated['avatar_remove'] ?? '0') === '1') {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle cover_image upload
        if (($validated['cover_image_remove'] ?? '0') === '1') {
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $user->cover_image = null;
        }
        if ($request->hasFile('cover_image')) {
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $user->cover_image = $request->file('cover_image')->store('covers', 'public');
        }

        $user->save();

        return response()->json([
            'status' => 'profile-updated'
        ]);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json(['status' => 'password-updated']);
    }
}
