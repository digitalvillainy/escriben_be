<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotebookController extends Controller
{

    //create a new Notebook for a user
    //TODO: Add validation
    public function createNotebook(Request $request): JsonResponse
    {
        Notebook::create([
            'title' => $request->title,
            'user_id' => $request->user_id
        ]);
        return $this->getNotebooks($request);
    }

    //return all the notebooks for a user
    public function getNotebooks(Request $request): JsonResponse
    {
        $notebooks = Notebook::where('user_id', $request->user_id)->get();
        return response()->json($notebooks);
    }

    //return notebook by id
    public function getNotebookById(Request $request): JsonResponse
    {
        $notebook = Notebook::where('id', $request->notebook_id)->get();
        return response()->json($notebook);
    }

    //Update a Notebook for a user
    public function updateNotebook(Request $request): JsonResponse
    {
        $notebook = Notebook::find($request->_id);
        $notebook->update([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return response()->json($notebook);
    }

    //Delete a Notebook for a user
    public function deleteNotebook(Request $request): JsonResponse
    {
        $notebook = Notebook::where('id', $request->notebook_id)->get();
        $notebook->delete();
        return response()->json($notebook);
    }
}
