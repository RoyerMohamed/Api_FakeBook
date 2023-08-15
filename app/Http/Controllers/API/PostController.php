<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Post::count()) {
            return response()->json(['message' => 'Pas de publication trouvé'], 404);
        }

        return response()->json(['message' => 'Publications trouvée', 'posts' => Post::all()], 200);
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
            'user_id' => 'int'
        ]);

        Post::create($data);

        return response()->json(['message' => 'La publication a bient été ajoutée'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Pas de publication trouvé'], 404);
        }

        return response()->json(['message' => 'Publication trouvée', 'post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Pas de publication trouvé'], 404);
        }

        $data = $request->validate([
            'content' => 'string',
            'image' => 'string',
            'tags' => 'string',
            'user_id' => 'int'
        ]);
        $post->update($data);

        return response()->json(['message' => 'La publication a bient été mise a jours', 'post' => $post], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Pas de publication trouvé'], 404);
        }

        Post::destroy($post->id);

        return response()->json(['message' => 'La publication a bient été supprimée '], 200);
    }
}
