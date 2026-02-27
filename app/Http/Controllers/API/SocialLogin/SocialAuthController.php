<?php

namespace App\Http\Controllers\API\SocialLogin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialAuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialAuthController extends Controller
{

    public function SocialLogin(Request $request)
    {
        $validated = $request->validate([
            'token'    => 'required',
            'provider' => 'required|in: google ,apple',
        ]);

        try {
            $provider = $validated['provider'];
            $token = $validated['token'];

            $socialiteUser = Socialite::driver($provider)->stateless()->userFromToken($token);

            if (!$socialiteUser || !$socialiteUser->getEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid social token or missing email.',
                ], 422);
            }

            $user = User::where('email', $socialiteUser->getEmail())
                ->where('provider', $provider)
                ->where('provider_id', $socialiteUser->getId())
                ->first();

            $isNewUser = false;

            if (!$user) {
                $password = Str::random(16);

                $user = User::create([
                    'first_name'  => $socialiteUser->getName() ? explode(' ', $socialiteUser->getName())[0] : 'Apple',
                    'last_name'   => $socialiteUser->getName() && count(explode(' ', $socialiteUser->getName())) > 1
                        ? explode(' ', $socialiteUser->getName())[1] : 'User',
                    'email'       => $socialiteUser->getEmail(),
                    'provider'    => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'password'    => Hash::make($password),
                    'role'        => Role::USER->value,
                ]);

                $isNewUser = true;
            }

            $jwt = auth('api')->login($user);

            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'User registered successfully.' : 'User logged in successfully.',
                'data'    => new SocialAuthResource($user),
                'token'   => $jwt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
