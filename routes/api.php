<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;

Route::post('/register',
    function (Request $request): JsonResponse {
        return (new AuthController())->registerUser($request);
});

Route::post('/login',
    function (Request $request): JsonResponse {
        return (new AuthController())->loginUser($request);
});

Route::post('/logout',
    function (Request $request): JsonResponse {
        return (new AuthController())->logout($request);
});

// TODO: Develop after other endpoints
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

