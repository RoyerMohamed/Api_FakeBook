<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($validatedData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::find(auth()->user()->id);
        
        $user->token = $user->createToken('userToken' . $user->id)->plainTextToken;

       // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => auth()->user(),
            'token' => $user->token,
        ]);
    }
}
