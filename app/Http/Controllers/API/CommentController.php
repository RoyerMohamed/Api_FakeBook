<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Comment::count()) {
            return response()->json(['message' => 'Pas de commentaires trouvé'], 200);
        }

        return response()->json(['message' => 'Commentairs trouvés', 'comments' => Comment::latest()->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // mise en place des validatore adéquate 
        $validator = Validator::make(
            $request->all(),
            [
                'content' => 'string',
                'image' => 'string',
                'tags' => 'string',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // mise en place de la saugarde de l'image en public
        if ($request->image) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }

        $comment = Comment::create($request->all());

        return response()->json(['message' => 'Le Commentaire a bient été ajouté', $comment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        if (!$comment) {
            return response()->json(['message' => 'Pas de commentaire trouvé'], 200);
        }

        return response()->json(['message' => 'Commentaire trouvé', 'comment' => $comment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // mise en place des validatore adéquate 
        $validator = Validator::make(
            $request->all(),
            [
                'content' => 'string',
                'image' => 'string',
                'tags' => 'string',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // mise en place de la saugarde de l'image en public
        if ($request->image && $request->image !== $comment->image) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }

        $comment->update($request->all());

        return response()->json(['message' => 'Le commentaire a bient été mise a jours', 'comment' => $comment], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {

        if (!$comment) {
            return response()->json(['message' => 'La resource n\'existe pas '], 404);
        }

        // Comment::destroy($comment->id);

        $comment->delete($comment->id);

        return response()->json(['message' => 'Le commentaire a bient été supprimée '], 200);
    }
}
