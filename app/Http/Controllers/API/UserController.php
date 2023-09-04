<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{


    public function __construct()
    {
        // j'evite de mettre ma route 'store' dans le middleware 
        // je peux aussi mettre only pour en rajouter
        $this->middleware("auth:sanctum")->except('store');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (User::count() == 0) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 200);
        }
        return response()->json(['message' => 'Utilisateurs trouvé', 'users' => User::latest()->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Je Vérifie les données fornit par le formulaire d'inscription avec 
        // Validator::make qui permet de ajouter des régle comme la longuer de la donnee, type accecter, etc... 
        $validator = Validator::make(
            $request->all(),
            [
                'pseudo' => 'max:50|min:8|required',
                'email' => 'required|email',
                'image' => 'mimes:jpeg,png,jpg|nullable',
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

        // je retourn l'utilisateur cree acompagner de sont token 
        return response()->json(
            [
                'message' => 'L\'utilisateur a été ajouté ',
                "user" => $user
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        if (!$user) {
            return response()->json(['message' => 'Pas d\'utilisateur trouvé'], 200);
        }

        return response()->json(['message' => ' Utilisateur trouvé' , $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'pseudo' => 'max:50|min:8|required',
                'email' => 'email',
                'image' => 'mimes:jpeg,png,jpg',
                'password' =>'nullable'
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

        if($request->password){
            $this->updatepassword($request , $user);
        }
        
        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully',  $user], 200);
    }


    private function updatepassword(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'nouveau_mdp' => ['required', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
            'confime_nouveau_mdp' => ['required', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
     
        if (Hash::check($request->ancien_mdp, $user->password)) {
            if ($request->ancien_mdp !== $request->Nouveau_mdp) {
                if ($request->Nouveau_mdp === $request->confime_nouveau_mdp) {
                    $user->password = Hash::make($request->Nouveau_mdp);
                    $user->save();
                    return response()->json(['message' => 'Votre mot de passe a bien été modifié'], 200);
                } else {
                    return response()->json(['message' => 'Votre nouveau mot de passe ne correspond pas avec la confirmation !'], 200);
                }
            } else {
                return response()->json(['message' => 'Votre nouveau mot de passe est identique avec ancien'], 200);
            }
        } else {
            return response()->json(['message' => 'Votre mot de passe actuel ne correspond pas ! '], 200);
        }

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
