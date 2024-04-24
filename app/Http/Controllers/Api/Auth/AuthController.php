<?php

namespace App\Http\Controllers\Api\Auth;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $user->tokens()->update(['expires_at' => now()]);
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => new UserResource($user)], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => new UserResource($user)], 201);
    }


    public function logout(Request $request)
    {
        // Revoke the current user's token
       
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.'], 200)
            : response()->json(['error' => 'Unable to send reset link.'], 400);
    }

    protected function broker()
    {
        return Password::broker();
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $response = $this->broker()->reset(
            $request->only('email', 'token', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
        return $response == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'], 200)
            : response()->json(['error' => 'Unable to reset password.'], 400);
    }

    public function changePassword(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.'], 200);
    }
}
