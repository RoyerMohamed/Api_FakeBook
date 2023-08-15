<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ( User::count() != 0 ) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 404);
        }
        return response()->json(['message' => 'Utilisateurs trouvé', 'users' => User::all()], 200) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userValidation = $request->validate([
            'pseudo' => 'max:50|min:8|required',
            'email' => 'required',
            'password' => Password::default(),
        ]); 
        
        return response()->json(['message' => 'L\'utilisateur a été ajouté ', 'user' => $userValidation ], 200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 404);
        }
        return $user; 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        if (!$user) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 404);
        }

        $data = $request->validate([
            'pseudo' => 'string', 
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:8',
        ]);

        $user->update($data);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        if (!$user) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 404);
        }

        User::destroy($user->id);
        return response()->json(['message' => 'L\'utilisateur a été supprimé '], 200);
    }
}
