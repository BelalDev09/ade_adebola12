<?php

namespace App\Http\Controllers\API;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'avater' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [
            'user_id' => $request->user_id,
        ];

        if ($request->hasFile('avater')) {
            $file = $request->file('avater');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profiles', $filename, 'public');

            $data['avater'] = 'profiles/' . $filename;
        }

        $profile = Profile::create($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                // 'data' => $data,
                'avater_url' => $profile->avater
                    ? asset('storage/' . $profile->avater)
                    : null,
            ]
        ], 201);
    }
}
