<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        // Redirect to Google authentication
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
   public function handleGoogleCallback()
{
    try {
        \Log::info('Handling Google callback...');
        $googleUser = Socialite::driver('google')->user();
        \Log::info('Google User Info:', (array) $googleUser);

        $existingUser = User::where('google_id', $googleUser->id)->first();
        if ($existingUser) {
            Auth::login($existingUser);
            \Log::info('Existing user logged in:', ['user_id' => $existingUser->id]);
        } else {
            // Create new user
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
            ]);
            Auth::login($newUser);
            \Log::info('New user created and logged in:', ['user_id' => $newUser->id]);
        }

        // Session management
        session(['username' => Auth::user()->name, 'id' => Auth::id()]);

        // Role check
        if (empty(Auth::user()->role)) {
            return redirect()->route('user.form'); 
        }

        return redirect()->intended('postjob');
    } catch (InvalidStateException $e) {
        \Log::error('Invalid state error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Invalid state. Please try logging in again.');
    } catch (\Exception $e) {
        \Log::error('Google login error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Something went wrong. Please try again.');
    }
}
    /**
     * Log the user out and clear session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Clear session data
        $request->session()->flush();

        // Optionally, invalidate the session
        $request->session()->invalidate();

        // Redirect to the login page
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
