<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                    'auth_token', ['*'], now()->addDays(2)
                );
                return response()->json([
                    'token' => $token->plainTextToken
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

    public function forgotPassword(Request $request){
        //TODO: Add logic for forgot password
    }


}
