<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',
    function (Request $request): \Illuminate\Http\JsonResponse {
        return (new AuthController())->registerUser($request);
});

Route::post('/login',
    function (Request $request): \Illuminate\Http\JsonResponse {
        return (new AuthController())->loginUser($request);
});

//TODO: add logout functionality
Route::post('/logout',
    function () {
        return 'hit';
        // return (new AuthController())->logout();
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

