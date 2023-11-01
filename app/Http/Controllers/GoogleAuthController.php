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
        return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/drive.readonly'])->redirect();
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
        session(['google_token' => $googleUser->token]);

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->away('https://accounts.google.com/Logout');
    }
}
