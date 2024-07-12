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
            'user_id' => $request->user()->user_id
        ]);
        return response()->json($Notebook);
    }

    //return all the notebooks for a user
    public function getNotebooks(Request $request): JsonResponse
    {
        $notebooks = Notebook::where('user_id', $request->user_id)->get();
        return response()->json($notebooks);
    }

    //Update a Notebook for a user
    public function updateNotebook(Request $request): JsonResponse
    {
        $notebook = Notebook::find($request->user_id);
        $notebook->update([
            'title' => $request->title
        ]);
        return response()->json($notebook);
    }

    //Delete a Notebook for a user
    public function deleteNotebook(Request $request): JsonResponse
    {
        $notebook = Notebook::find($request->user_id);
        $notebook->delete();
        return response()->json($notebook);
    }
}
