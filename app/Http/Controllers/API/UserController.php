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
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $userValidation = User::create($request->all()); 
        $userValidation = $request->validate([
            'pseudo' => 'max:50|min:8|required',
            'email' => 'required',
            'password' => Password::default(),
        ]); 
        
        return response()->json($userValidation , 200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user; 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
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
        //
    }
}
