<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info('Handling Google callback...');

        // Fetch the user from Google
        $googleUser = Socialite::driver('google')->stateless()->user();
        Log::info('Google User Info:', (array) $googleUser);

        // Find user by Google ID
        $existingUser = User::where('google_id', $googleUser->id)->first();

        if ($existingUser) {
            // Log the existing user in
            Auth::login($existingUser);
            Log::info('Existing user logged in:', ['user_id' => $existingUser->id]);
        } else {
            // If no user exists, create a new one
            try {
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'contact_num' => null,
                    'birthdate' => null,
                    'role' => null, // The role is set to null initially
                ]);
                Auth::login($newUser);
                Log::info('New user created and logged in:', ['user_id' => $newUser->id]);
            } catch (\Exception $e) {
                Log::error('User creation error: ' . $e->getMessage());
                return redirect()->route('login')->with('error', 'Could not create user account. Please try again.');
            }
        }

        // Set the session ID
        session(['id' => Auth::id()]);  // Store the authenticated user's ID in the session

        // Check if the user's role is empty, redirect to the user form if so
        if (is_null(Auth::user()->role)) {
            Log::info('User missing role, redirecting to form.', ['user_id' => Auth::id()]);
            return redirect()->route('user.form'); 
        }

        // Redirect to post job page if role exists
        Log::info('User logged in successfully, redirecting to postjob.', ['user_id' => Auth::id()]);
        return redirect()->intended('postjob');

    } catch (InvalidStateException $e) {
        Log::error('Invalid state error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Invalid state. Please try logging in again.');
    } catch (\Exception $e) {
        Log::error('Google login error: ' . $e->getMessage());
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
