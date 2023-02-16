<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    /**
     * Redirect to Google OAuth2 proccess
     *
     * @return RedirectResponse
     */
    public function googleRedirect():RedirectResponse{
        return Socialite::driver('google')->redirect();
    }

    /**
     * Google OAuth2 callback
     *
     * @return RedirectResponse
     */
    public function googleCallback(): RedirectResponse
    {

        $googleUser = Socialite::driver('google')->user();

        Log::info("[Socialite] Logged in using Google #" . $googleUser->id);

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
