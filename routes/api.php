<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\NotesController;
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
* Description: Forgot password
* Body: email
*/
Route::post('/forgot-password',
    function (Request $request): JsonResponse {
        return (new AuthController())->forgotPassword($request);
})->middleware('guest')->name('password.reset');

/*
* Description: Retrieve password token
* Body: email
*/
Route::get('/reset-password',
    function (Request $request): JsonResponse {
        return (new AuthController())->resetPassword($request);
})->middleware('guest')->name('password.update');

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
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//NOTE: Be sure to send bearer token in header
Route::group(['middleware' => 'auth:sanctum'], function () {
    /**
     * Description: Will get all the notebooks for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::get('/notebooks', function (Request $request): JsonResponse {
        return (new NotebookController())->getNotebooks($request);
    });

    /**
     * Description: Will get a Notebook by notebook id
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::get('/notebooks/{id}', function (Request $request): JsonResponse {
        return (new NotebookController())->getNotebookById($request);
    });

    /**
     * Description: Will create a new Notebook for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::post('/notebooks', function (Request $request): JsonResponse {
        return (new NotebookController())->createNotebook($request);
    });

    /**
     * Description: Will update a Notebook for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::patch('/notebooks', function (Request $request): JsonResponse {
        return (new NotebookController())->updateNotebook($request);
    });

    /**
     * Description: Will delete a Notebook for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::delete('/notebooks/{id}', function (Request $request): JsonResponse {
        return (new NotebookController())->deleteNotebook($request);
    });
});

//NOTE: Be sure to send bearer token in header
Route::group(['middleware' => 'auth:sanctum'], function () {
    /**
     * Description: Will get all the notes in the notebook
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::get('/notes', function (Request $request): JsonResponse {
        return (new NotesController())->getNotesByNotebook($request);
    });

    /**
     * Description: Will create a new Note for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::post('/notes', function (Request $request): JsonResponse {
        return (new NotesController())->createNote($request);
    });

    /**
     * Description: Will update a Note for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::patch('/notes', function (Request $request): JsonResponse {
        return (new NotesController())->updateNote($request);
    });

    /**
     * Description: Will delete a Note for a user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    Route::delete('/notes', function (Request $request): JsonResponse {
        return (new NotesController())->deleteNote($request);
    });
});
