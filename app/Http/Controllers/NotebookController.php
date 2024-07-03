<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotebookController extends Controller
{

    //create a new Notebook for a user
    public function createNotebook(Request $request): JsonResponse
    {
        $Notebook = Notebook::create([
            'title' => $request->title,
            'user_id' => $request->user()->id
        ]);
        return response()->json($Notebook);
    }

    //return all the notebooks for a user
    public function getNotebooks(Request $request): JsonResponse
    {
        $notebooks = Notebook::where('user_id', $request->user()->id)->get();
        return response()->json($notebooks);
    }
}
