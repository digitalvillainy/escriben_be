<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;

/*
* Description: Creates a new user
* Body: username, first_name, last_name, email, password
*/
Route::post('/register',
    function (Request $request): JsonResponse {
        return (new AuthController())->registerUser($request);
});


/*
* Description: Will login a user and return a token
* Body: email, password
*/
Route::post('/login',
    function (Request $request): JsonResponse {
        return (new AuthController())->loginUser($request);
});

/*
* NOTE: Be sure to send bearer token in header
    * Description: Will logout current authenticated user
    * Body: authenticated user email
    * Content-Type: application/json
    * Authorization: Bearer <token>
*/
Route::post('/logout',
    function (Request $request): JsonResponse {
        return (new AuthController())->logout($request);
})->middleware('auth:sanctum');

/*
* NOTE: Be sure to send bearer token in header
    * Description: Will get current authenticated user
    * Content-Type: application/json
    * Authorization: Bearer <token>
*/
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});

//TODO: work on User model and updating User create table

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/notebooks', function (Request $request) {
        return $request->notebooks();
    });

    Route::get('/notebooks/{notebook}', function (Request $request, $notebook) {
        return $request->notebook($notebook);
    });

    Route::post('/notebooks', function (Request $request) {
        return $request->createNotebook($request->title);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/notes', function (Request $request) {
        return $request->notes();
    });

    Route::post('/notes', function (Request $request) {
        return $request->createNote($request->title);
    });

    Route::get('/notes/{note}', function (Request $request, $note) {
        return $request->note($note);
    });
});
