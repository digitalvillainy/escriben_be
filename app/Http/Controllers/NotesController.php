<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    //Create a new note
    public function createNote(Request $request): JsonResponse
    {
        $note = Notes::create([
            'title' => $request->title,
            'content' => $request->content,
            'notebook_id' => $request->notebook_id
        ]);

        return response()->json($note);
    }

    //return a note by id
    public function getNoteByTitle(Request $request): JsonResponse
    {
        $note = Notes::where('title', $request->title)->first();
        return response()->json($note);
    }

    //return all the notes in the notebook
    public function getNotesByNotebook(Request $request): JsonResponse
    {
        $notes = Notes::where('notebook_id', $request->id)->get();
        return response()->json($notes);
    }

    //Delete a note
    public function deleteNote(Request $request): JsonResponse
    {
        $note = Notes::find($request->id);
        $note->delete();
        return response()->json($note);
    }

    //Update a note
    public function updateNote(Request $request): JsonResponse
    {
        $note = Notes::find($request->id);
        $note->update([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return response()->json($note);
    }
}
