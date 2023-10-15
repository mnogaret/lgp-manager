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

        if (!config('app.google_auth_enabled')) {
            // Ici, vous pouvez rediriger vers une page d'erreur ou simplement authentifier l'utilisateur localement sans passer par Google.
            $user = User::where('email', "utilisateur@example.com")->first();
            if (!$user) {
                $user = User::create([
                    'name' => "Utilisateur",
                    'email' => "utilisateur@example.com",
                ]);
            }
            Auth::login($user, true);
            return redirect()->intended('/'); // ou toute autre action que vous souhaitez effectuer
        }

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
