<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use RuntimeException;

class AuthController extends Controller
{
    /**
     * NOTE: Register new user
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function registerUser(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|unique:users',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $result = $user->save();

        if($result){
            $token = $user->createToken(
                'auth_token', ['*'], now()->addDays(2)
            );
            return response()->json([
                'token' => $token->plainTextToken
            ]);
        }
    }

    /**
     * NOTE: Login user and create token
     * @param Request $request
     * @return JsonResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function loginUser(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken(
                    'token', ['*'], now()->addDays(2)
                );
                return response()->json([
                    'token' => $token->plainTextToken,
                    'user' => $user
                ]);
            }else{
                return response()->json([
                    'status' => 'Invalid credentials'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'Invalid credentials'
            ]);
        }
    }

    /**
     * NOTE: Logout user and revoke token
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function logout(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user){
            $user->tokens()->delete();
            return response()->json([
                'status' => 'logged out'
            ]);
        }
        return response()->json([
            'status' => 'Something went wrong'
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
                'status' => __($status)
            ]);
        } else {
            return response()->json([
                'email' => __($status)
            ]);
        }
    }

    //Handle Reset Password
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => __($status)
            ]);
        } else {
            return response()->json([
                'email' => __($status)
            ]);
        }
    }

}
