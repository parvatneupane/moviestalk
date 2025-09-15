<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google OAuth page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect(); // no stateless for local
    }

    /**
     * Handle callback from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name'      => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => null, // Google login, no password
                ]
            );

            // Login the user
            Auth::login($user);

            // Redirect to dashboard
            return redirect('/dashboard');

        } catch (\Exception $e) {
            // If something goes wrong, redirect to login with error
            return redirect('/login')->with('error', 'Google login failed: '.$e->getMessage());
        }
    }
}
