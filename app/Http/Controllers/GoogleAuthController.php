<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        session(['url.intended' => url()->previous()]);
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Recherchez l'utilisateur dans votre base de données par e-mail
        $user = User::where('email', $googleUser->email)->first();

        // Si l'utilisateur n'existe pas, créez-le
//        if (!$user) {
//            $user = User::create([
//                'name' => $googleUser->name,
//                'email' => $googleUser->email,
//                // ... autres champs que vous souhaitez stocker
//            ]);
//        }

        // Authentifiez l'utilisateur
        Auth::login($user, true);

        return redirect()->intended('/'); // '/' est l'URL par défaut si aucune URL d'origine n'est trouvée
    }
}
