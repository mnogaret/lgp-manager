<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
                    'email' => "utilisateur@example.com",
                    'first_name' => "Util",
                    'last_name' => "Isateur",
                    'avatar' => "https://images.unsplash.com/photo-1502378735452-bc7d86632805"
                ]);
            }
            Auth::login($user, true);
            return redirect()->intended('/');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Recherchez l'utilisateur dans votre base de données par e-mail
        $user = User::where('email', $googleUser->email)->first();

        // Si l'utilisateur n'existe pas, créez-le
        if (!$user) {
            return abort(403, 'Vous n’avez pas l’autorisation d’accéder à cette page.');
        }
        $user->update([
            'first_name' => $googleUser->user['given_name'],
            'last_name' => $googleUser->user['family_name'],
            'avatar' => $googleUser->user['picture'],
        ]);

        // Authentifiez l'utilisateur
        Auth::login($user, true);

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->away('https://accounts.google.com/Logout');
    }
}
