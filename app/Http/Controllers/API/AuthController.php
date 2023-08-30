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
    public function registerUser(Request $request)
    {

        // Je Vérifie les données fornit par le formulaire d'inscription avec 
        // Validator::make qui permet de ajouter des régle comme la longuer de la donnee, type accecter, etc... 
        $validator = Validator::make(
            $request->all(),
            [
                'pseudo' => 'max:50|min:8|required',
                'email' => 'required|email',
                'image' => 'mimes:jpeg,png,jpg',
                'password' => 'required', Password::min(8)->letters()->mixedCase()->numbers(),
            ]
        );
        // Si les information fournit par l'utilateur ne coresponde pas au format des valeurs demander 
        // je renvoie un messages d'erreur 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // mise en place de la saugarde de l'image en public
        if ($request->image) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }
        // creation de l'utilisater
        $user = User::create([
            'pseudo' => $request->pseudo,
            'email' =>  $request->email,
            'image' =>  $request->image,
            'password' => Hash::make($request->password)
        ]);
        // creation du token en claire
        $token = $user->createToken('auth_token')->plainTextToken;
        // je retourn l'utilisateur cree acompagner de sont token 
        return response()->json(
            [
                'message' => 'L\'utilisateur a été ajouté ',
                "user" => $user,
                "token" => $token
            ],
            200
        );
    }


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
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => auth()->user(),
            'token' => $token,
        ]);
    }
}
