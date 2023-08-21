<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Post::count()) {
            return response()->json(['message' => 'Pas de publication trouvé'], 200);
        }

        return response()->json(['message' => 'Publications trouvée', 'posts' => Post::latest()->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des donnée reçu 
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'image' => 'mimes:jpeg,png,jpg',
            'tags' => 'required|string|max:255',
            'user_id' => 'int|max:1'
            ]
        );

        // Gestion des erreurs du validateur
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Gestion de la sauvegarde de l'image
        if ($request->image) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }

      $post =   Post::create($request->all());

        return response()->json(['message' => 'La publication a bient été ajoutée', $post], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Pas de publication trouvé'], 200);
        }

        return response()->json(['message' => 'Publication trouvée', 'post' => $post], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        if (!$post) {
            return response()->json(['message' => 'Pas de publication trouvé'], 200);
        } 

         // Validation des donnée reçu 
         $validator = Validator::make($request->all(), [
            'content' => 'string|max:1000',
            'image' => 'mimes:jpeg,png,jpg',
            'tags' => 'string|max:255',
            'user_id' => 'int|max:1'
            ]
        );

        // Gestion des erreurs du validateur
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Gestion de la sauvegarde de l'image
        if ($request->image) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }

        $post->update($request->all());

        return response()->json(['message' => 'La publication a bient été mise a jours', 'Publication' => $post], 200);
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
