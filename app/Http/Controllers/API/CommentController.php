<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Comment::count()) {
            return response()->json(['message' => 'Pas de commentaires trouvé'], 404);
        }

        return response()->json(['message' => 'Commentairs trouvée', 'comments' => Comment::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'string',
            'image' => 'string',
            'tags' => 'string',
            'user_id' => 'int', 
            'post_id'=> 'int'
        ]);

        Comment::create($data);

        return response()->json(['message' => 'Le Commentaire a bient été ajoutée'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        if (!$comment) {
            return response()->json(['message' => 'Pas de commentaire trouvé'], 404);
        }

        return response()->json(['message' => 'Commentaire trouvée', 'comment' => $comment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        
        if (!$comment) {
            return response()->json(['message' => 'Pas de commentaire trouvé'], 404);
        }

        $data = $request->validate([
            'content' => 'string',
            'image' => 'string',
            'tags' => 'string',
            'user_id' => 'int'
        ]);
        $comment->update($data);

        return response()->json(['message' => 'Le commentaire a bient été mise a jours', 'comment' => $comment], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        
        if (!$comment) {
            return response()->json(['message' => 'Pas de commentaire trouvé'], 404);
        }

        Comment::destroy($comment->id);

        return response()->json(['message' => 'Le commentaire a bient été supprimée '], 200);
    
    }
}
