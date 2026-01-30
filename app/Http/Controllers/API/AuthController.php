<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['user'] =  $user;

        return $this->sendResponse($success, 'User register successfully.');
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $success = $this->respondWithToken($token);

        return $this->sendResponse($success, 'User login successfully.');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = auth('api')->user();

        return $this->sendResponse($success, 'Profile show successfully.');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->sendResponse([], 'Successfully logged out.');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(auth('api')->refresh());

        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    // forget password
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp_hash' => Hash::make($otp),
                'is_verified' => false,
                'reset_token' => null,
                'created_at' => now()
            ]
        );

        Mail::raw(
            "Your password reset OTP is: $otp\nThis OTP will expire in 10 minutes.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset OTP');
            }
        );

        return response()->json([
            'message' => 'OTP sent to your email'
        ], 200);
    }

    // verify otp

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        if ($reset->is_verified) {
            return response()->json(['message' => 'OTP already verified'], 400);
        }

        if (now()->diffInMinutes($reset->created_at) > 10) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        if (!Hash::check($request->otp, $reset->otp_hash)) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        $resetToken = Str::random(60);

        DB::table('password_resets')
            ->where('email', $request->email)
            ->update([
                'is_verified' => true,
                'reset_token' => hash('sha256', $resetToken)
            ]);

        return response()->json([
            'reset_token' => $resetToken,
            'message' => 'OTP verified successfully'
        ], 200);
    }


    // reset password

    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $reset = DB::table('password_resets')
            ->where('reset_token', hash('sha256', $request->reset_token))
            ->where('is_verified', true)
            ->first();

        if (!$reset) {
            return response()->json([
                'message' => 'Invalid or expired reset token'
            ], 403);
        }

        // Reset token expiry check
        if (now()->diffInMinutes($reset->created_at) > 15) {
            return response()->json([
                'message' => 'Reset token expired'
            ], 400);
        }

        User::where('email', $reset->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_resets')
            ->where('email', $reset->email)
            ->delete();

        return response()->json([
            'message' => 'Password reset successful'
        ], 200);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
