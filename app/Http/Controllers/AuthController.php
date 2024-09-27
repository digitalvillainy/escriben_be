<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Base64;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use RuntimeException;

class AuthController extends Controller
{
    /**
     * NOTE: Register new user
     *
     * @throws BindingResolutionException
     */
    public function registerUser(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|unique:users',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $result = $user->save();

        if ($result) {
            $token = $user->createToken(
                'auth_token', ['*'], now()->addDays(2)
            );

            $user->profile_pic = null;
            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ]);
        }
    }

    /**
     * NOTE: Login user and create token
     *
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function loginUser(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken(
                    'token', ['*'], now()->addDays(2)
                );

                return response()->json([
                    'token' => $token->plainTextToken,
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 'Invalid credentials',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'Invalid credentials',
            ]);
        }
    }

    /**
     * NOTE: Logout user and revoke token
     *
     * @throws BindingResolutionException
     */
    public function logout(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                'status' => 'logged out',
            ]);
        }

        return response()->json([
            'status' => 'Something went wrong',
        ]);
    }

    //Handles password reset form
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => __($status),
            ]);
        } else {
            return response()->json([
                'email' => __($status),
            ]);
        }
    }

    //Upload Profile Picture with a base64 image
    public function uploadProfilePic(Request $request): JsonResponse
    {
        $request->validate([
            'profile_pic' => ['required', new Base64],
            'id' => 'required',
        ]);

        $response = User::where('id', $request->id)->first();

        if ($response) {
            $response->profile_pic = $request->profile_pic;
            $response->save();
        }

        return response()->json($response);
    }

    //Handle Reset Password
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => __($status),
            ]);
        } else {
            return response()->json([
                'email' => __($status),
            ]);
        }
    }

    //Update user
    public function updateUser(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'nullable|email',
            'username' => 'string',
        ]);

        $user = User::where('id', $request->id)->first();
        if ($user) {

            $data = $request->all();
            $user->fill($data);
            $user->save();

            return response()->json([
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 'Something went wrong',
        ]);
    }
}
