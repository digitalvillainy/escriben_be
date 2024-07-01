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
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function registerUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $result = $user->save();

        return response()->json([
            'status' => $result
        ]);
    }

    /**
     * Login user and create token
     * @param Request $request
     * @return JsonResponse
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function loginUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('auth_token');
                return response()->json([
                    'token' => $token->plainTextToken
                ]);
            }else{
                return response()->json([
                    'status' => 'Invalid credentials'
                ]);
            }
        }
    }

    /**
     * Logout user and revoke token
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
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

}
